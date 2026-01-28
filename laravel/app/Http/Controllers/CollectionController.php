<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Publisher;
use App\Models\CollectionType;
use App\Models\Classification;
use App\Models\Gmd;
use App\Models\Subject;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:collections.view')->only(['index', 'show']);
        $this->middleware('permission:collections.create')->only(['create', 'store']);
        $this->middleware('permission:collections.edit')->only(['edit', 'update']);
        $this->middleware('permission:collections.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Collection::query();

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by collection type
        if ($request->collection_type_id) {
            $query->where('collection_type_id', $request->collection_type_id);
        }

        // Filter by availability
        if ($request->available_only) {
            $query->where('available_items', '>', 0);
        }

        $collections = $query->with(['publisher', 'classification', 'collectionType', 'gmd'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get filter options
        $collectionTypes = CollectionType::all();

        return view('admin.collections.index', compact('collections', 'collectionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $publishers = Publisher::all();
        $collectionTypes = CollectionType::all();
        $classifications = Classification::orderBy('code')->get();
        $gmds = Gmd::all();
        $subjects = Subject::all();

        return view('admin.collections.create', compact(
            'publishers',
            'collectionTypes',
            'classifications',
            'gmds',
            'subjects'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'isbn' => 'nullable|string|max:50|unique:collections,isbn',
            'issn' => 'nullable|string|max:50',
            'publisher_id' => 'nullable|exists:publishers,id',
            'year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'edition' => 'nullable|string|max:100',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'classification_id' => 'nullable|exists:classifications,id',
            'collection_type_id' => 'nullable|exists:collection_types,id',
            'gmd_id' => 'nullable|exists:gmds,id',
            'abstract' => 'nullable|string',
            'description' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'total_items' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['authors'] = $validated['authors'];
        $validated['subjects'] = $validated['subjects'] ?? [];
        $validated['available_items'] = $validated['total_items'];
        $validated['borrowed_items'] = 0;

        $collection = Collection::create($validated);

        // Sync subjects
        if (!empty($validated['subjects'])) {
            $collection->subjects()->attach($validated['subjects']);
        }

        return redirect()
            ->route('collections.show', $collection)
            ->with('success', 'Koleksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $collection->load(['publisher', 'classification', 'collectionType', 'gmd', 'subjects']);

        // Get items
        $items = $collection->items()
            ->with('branch')
            ->latest()
            ->paginate(10);

        // Get loans history
        $loans = $collection->loans()
            ->with(['member', 'loanBranch'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.collections.show', compact('collection', 'items', 'loans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        $publishers = Publisher::all();
        $collectionTypes = CollectionType::all();
        $classifications = Classification::orderBy('code')->get();
        $gmds = Gmd::all();
        $subjects = Subject::all();

        $collection->load(['subjects']);

        return view('admin.collections.edit', compact(
            'collection',
            'publishers',
            'collectionTypes',
            'classifications',
            'gmds',
            'subjects'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'isbn' => 'nullable|string|max:50|unique:collections,isbn,' . $collection->id,
            'issn' => 'nullable|string|max:50',
            'publisher_id' => 'nullable|exists:publishers,id',
            'year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'edition' => 'nullable|string|max:100',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'classification_id' => 'nullable|exists:classifications,id',
            'collection_type_id' => 'nullable|exists:collection_types,id',
            'gmd_id' => 'nullable|exists:gmds,id',
            'abstract' => 'nullable|string',
            'description' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'total_items' => 'required|integer|min:' . $collection->borrowed_items,
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($collection->cover_image && Storage::disk('public')->exists($collection->cover_image)) {
                Storage::disk('public')->delete($collection->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['authors'] = $validated['authors'];
        $validated['subjects'] = $validated['subjects'] ?? [];

        // Update available items based on total
        $borrowedCount = $collection->borrowed_items;
        $validated['available_items'] = $validated['total_items'] - $borrowedCount;

        $collection->update($validated);

        // Sync subjects
        $collection->subjects()->sync($validated['subjects']);

        return redirect()
            ->route('collections.show', $collection)
            ->with('success', 'Koleksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        // Check if there are active loans
        if ($collection->borrowed_items > 0) {
            return redirect()
                ->route('collections.show', $collection)
                ->with('error', 'Tidak dapat menghapus koleksi yang masih memiliki item yang dipinjam.');
        }

        $collection->delete();

        return redirect()
            ->route('collections.index')
            ->with('success', 'Koleksi berhasil dihapus.');
    }
}
