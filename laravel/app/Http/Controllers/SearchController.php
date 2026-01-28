<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Author;
use App\Models\Subject;
use App\Models\Publisher;
use App\Models\Gmd;
use App\Models\CollectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display the OPAC homepage.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_collections' => Collection::count(),
            'total_items' => CollectionItem::count(),
            'available_items' => CollectionItem::where('status', 'available')->count(),
            'total_authors' => Author::count(),
        ];

        // Get recent collections
        $recentCollections = Collection::with(['collectionType', 'publisher', 'gmd'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get popular collections (most borrowed)
        $popularCollections = Collection::with(['collectionType', 'publisher', 'gmd'])
            ->orderBy('borrowed_items', 'desc')
            ->limit(10)
            ->get();

        return view('public.opac.index', compact('stats', 'recentCollections', 'popularCollections'));
    }

    /**
     * Perform search and display results.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);

        // Build search query
        $collectionsQuery = Collection::with(['subjects', 'publisher', 'collectionType', 'gmd'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('isbn', 'like', '%' . $query . '%')
                    ->orWhere('issn', 'like', '%' . $query . '%')
                    ->orWhere('abstract', 'like', '%' . $query . '%');
            });

        // Apply filters
        if ($request->filled('collection_type')) {
            $collectionsQuery->where('collection_type_id', $request->collection_type);
        }

        if ($request->filled('gmd')) {
            $collectionsQuery->where('gmd_id', $request->gmd);
        }

        // Filter by author (search in JSON column)
        if ($request->filled('author')) {
            $author = Author::find($request->author);
            if ($author) {
                $collectionsQuery->whereJsonContains('author_ids', $author->id);
            }
        }

        if ($request->filled('subject')) {
            $collectionsQuery->whereHas('subjects', function ($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }

        if ($request->filled('publisher')) {
            $collectionsQuery->where('publisher_id', $request->publisher);
        }

        if ($request->filled('language')) {
            $collectionsQuery->where('language', $request->language);
        }

        if ($request->filled('year_from')) {
            $collectionsQuery->where('year', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $collectionsQuery->where('year', '<=', $request->year_to);
        }

        if ($request->filled('available_only')) {
            $collectionsQuery->where('available_items', '>', 0);
        }

        // Paginate results
        $collections = $collectionsQuery->paginate(20)->withQueryString();

        // Get filter options
        $filterOptions = [
            'collection_types' => CollectionType::orderBy('name')->get(),
            'gmds' => Gmd::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'languages' => Collection::select('language')->distinct()->whereNotNull('language')->pluck('language')->sort(),
        ];

        return view('public.opac.search', compact('collections', 'query', 'filterOptions'));
    }

    /**
     * Display collection detail (OPAC record).
     */
    public function show($id)
    {
        $collection = Collection::with([
            'subjects',
            'publisher',
            'collectionType',
            'gmd',
            'items.branch'
        ])->findOrFail($id);

        // Get available items
        $availableItems = $collection->items()->where('status', 'available')->get();
        $totalAvailable = $availableItems->count();

        // Get related collections (same subjects)
        $relatedCollections = Collection::with(['collectionType', 'publisher'])
            ->where('id', '!=', $collection->id)
            ->whereHas('subjects', function ($q) use ($collection) {
                $subjectIds = $collection->subjects->pluck('id');
                $q->whereIn('subjects.id', $subjectIds);
            })
            ->limit(6)
            ->get();

        return view('public.opac.show', compact('collection', 'totalAvailable', 'relatedCollections'));
    }

    /**
     * Autocomplete for search suggestions.
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search collections
        $collections = Collection::select('id', 'title', 'cover_image')
            ->where('title', 'like', '%' . $query . '%')
            ->limit(8)
            ->get()
            ->map(function ($collection) {
                return [
                    'type' => 'collection',
                    'id' => $collection->id,
                    'title' => $collection->title,
                    'url' => route('opac.show', $collection->id),
                    'cover' => $collection->cover_image ? asset('storage/' . $collection->cover_image) : null,
                ];
            });

        // Search authors
        $authors = Author::select('id', 'name')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->get()
            ->map(function ($author) {
                return [
                    'type' => 'author',
                    'id' => $author->id,
                    'title' => $author->name,
                    'url' => route('opac.search', ['author' => $author->id]),
                ];
            });

        // Search subjects
        $subjects = Subject::select('id', 'name')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->get()
            ->map(function ($subject) {
                return [
                    'type' => 'subject',
                    'id' => $subject->id,
                    'title' => $subject->name,
                    'url' => route('opac.search', ['subject' => $subject->id]),
                ];
            });

        $results = $collections->concat($authors)->concat($subjects)->take(15);

        return response()->json($results);
    }

    /**
     * Advanced search page.
     */
    public function advanced()
    {
        $filterOptions = [
            'collection_types' => CollectionType::orderBy('name')->get(),
            'gmds' => Gmd::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'languages' => Collection::select('language')->distinct()->whereNotNull('language')->pluck('language')->sort(),
        ];

        return view('public.opac.advanced', compact('filterOptions'));
    }
}
