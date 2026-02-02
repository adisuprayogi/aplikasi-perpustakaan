<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    /**
     * Get all collections with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Collection::query()->with(['publisher', 'classification', 'collectionType']);

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by collection type
        if ($request->collection_type_id) {
            $query->where('collection_type_id', $request->collection_type_id);
        }

        // Filter by classification
        if ($request->classification_id) {
            $query->where('classification_id', $request->classification_id);
        }

        // Filter by availability
        if ($request->available_only) {
            $query->where('available_items', '>', 0);
        }

        $collections = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $collections->items(),
            'meta' => [
                'current_page' => $collections->currentPage(),
                'per_page' => $collections->perPage(),
                'total' => $collections->total(),
                'last_page' => $collections->lastPage(),
            ],
        ]);
    }

    /**
     * Get specific collection.
     */
    public function show(Collection $collection): JsonResponse
    {
        $collection->load(['publisher', 'classification', 'collectionType', 'gmd', 'subjects', 'items']);

        return response()->json([
            'success' => true,
            'data' => $collection,
        ]);
    }

    /**
     * Get available items for a collection.
     */
    public function items(Collection $collection): JsonResponse
    {
        $items = $collection->items()
            ->where('status', 'available')
            ->with('branch')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Search collections.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $collections = Collection::search($request->q)
            ->with(['publisher', 'classification'])
            ->limit($request->limit ?? 20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $collections,
        ]);
    }

    /**
     * Get statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_collections' => Collection::count(),
            'total_items' => CollectionItem::count(),
            'available_items' => CollectionItem::where('status', 'available')->count(),
            'borrowed_items' => CollectionItem::where('status', 'borrowed')->count(),
            'by_collection_type' => Collection::query()
                ->selectRaw('collection_type_id, COUNT(*) as count')
                ->with('collectionType')
                ->groupBy('collection_type_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => $item->collectionType?->name ?? 'Unknown',
                        'count' => $item->count,
                    ];
                }),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get collection by ISBN/ISSN.
     */
    public function findByIsbn(Request $request): JsonResponse
    {
        $request->validate([
            'isbn' => 'required|string',
        ]);

        $collection = Collection::where('isbn', $request->isbn)
            ->orWhere('issn', $request->isbn)
            ->with(['publisher', 'classification', 'collectionType'])
            ->first();

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $collection,
        ]);
    }
}
