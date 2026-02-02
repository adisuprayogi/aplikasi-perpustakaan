<?php

namespace App\Http\Controllers;

use App\Models\DigitalFile;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DigitalFileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:digital_files.view')->only(['index', 'show', 'download', 'preview']);
        $this->middleware('permission:digital_files.create')->only(['create', 'store']);
        $this->middleware('permission:digital_files.edit')->only(['edit', 'update']);
        $this->middleware('permission:digital_files.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DigitalFile::query()->with(['collection', 'uploader']);

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by access level
        if ($request->access_level) {
            $query->where('access_level', $request->access_level);
        }

        // Filter by collection
        if ($request->collection_id) {
            $query->where('collection_id', $request->collection_id);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $digitalFiles = $query->latest()->paginate(20)->withQueryString();

        // Get filter options
        $collections = Collection::all();

        return view('admin.digital-files.index', compact('digitalFiles', 'collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $collectionId = $request->query('collection_id');
        $collection = $collectionId ? Collection::find($collectionId) : null;
        $collections = Collection::all();

        return view('admin.digital-files.create', compact('collections', 'collection'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'title' => 'required|string|max:500',
            'file' => 'required|file|max:' . (config('filesystems.max_upload_size', 102400)), // Max 100MB default
            'access_level' => 'required|in:public,registered,campus_only',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('digital-files', $fileName, 'local');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['mime_type'] = $file->getMimeType();
        }

        $validated['uploaded_by'] = Auth::id();
        $validated['download_count'] = 0;
        $validated['view_count'] = 0;
        // Ensure boolean for is_active (checkbox with hidden input sends "0" or "1")
        $validated['is_active'] = isset($validated['is_active']) && (bool) $validated['is_active'];

        // If published_at is not set and is_active is true, set it to now
        if (empty($validated['published_at']) && $validated['is_active']) {
            $validated['published_at'] = now();
        }

        DigitalFile::create($validated);

        return redirect()
            ->route('digital-files.index')
            ->with('success', 'File digital berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DigitalFile $digitalFile)
    {
        $digitalFile->load(['collection', 'uploader']);

        return view('admin.digital-files.show', compact('digitalFile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DigitalFile $digitalFile)
    {
        $collections = Collection::all();
        $digitalFile->load(['collection', 'uploader']);

        return view('admin.digital-files.edit', compact('digitalFile', 'collections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DigitalFile $digitalFile)
    {
        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'title' => 'required|string|max:500',
            'access_level' => 'required|in:public,registered,campus_only',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle file replacement
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('digital-files', $fileName, 'local');

            // Delete old file
            if ($digitalFile->file_path && Storage::exists($digitalFile->file_path)) {
                Storage::delete($digitalFile->file_path);
            }

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['mime_type'] = $file->getMimeType();
        }

        // Ensure boolean for is_active (checkbox with hidden input sends "0" or "1")
        $validated['is_active'] = isset($validated['is_active']) && (bool) $validated['is_active'];

        // If published_at is not set and is_active is being changed to true, set it to now
        if (empty($validated['published_at']) && $validated['is_active'] && !$digitalFile->is_active) {
            $validated['published_at'] = now();
        }

        $digitalFile->update($validated);

        return redirect()
            ->route('digital-files.show', $digitalFile)
            ->with('success', 'File digital berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DigitalFile $digitalFile)
    {
        // Delete physical file
        if ($digitalFile->file_path && Storage::exists($digitalFile->file_path)) {
            Storage::delete($digitalFile->file_path);
        }

        $digitalFile->delete();

        return redirect()
            ->route('digital-files.index')
            ->with('success', 'File digital berhasil dihapus.');
    }

    /**
     * Download the specified digital file.
     */
    public function download(DigitalFile $digitalFile)
    {
        // Check access
        if (!$digitalFile->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Check if file exists
        if (!$digitalFile->fileExists()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Increment download count
        $digitalFile->incrementDownloadCount();

        return Storage::download($digitalFile->file_path, $digitalFile->file_name);
    }

    /**
     * Preview the specified digital file (for PDF and images).
     */
    public function preview(DigitalFile $digitalFile)
    {
        // Check access
        if (!$digitalFile->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Check if file exists
        if (!$digitalFile->fileExists()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Only PDF and images can be previewed
        if (!$digitalFile->isPdf() && !$digitalFile->isImage()) {
            return redirect()
                ->route('digital-files.download', $digitalFile)
                ->with('info', 'Tipe file ini tidak dapat dipreview, silakan download.');
        }

        // Increment view count
        $digitalFile->incrementViewCount();

        return view('admin.digital-files.preview', compact('digitalFile'));
    }

    /**
     * Display a listing of digital files for public.
     */
    public function publicIndex(Request $request)
    {
        $query = DigitalFile::query()
            ->active()
            ->published()
            ->with(['collection']);

        // Get user
        $user = Auth::user();

        // Filter by access level based on user
        if (!$user) {
            // Guest users can only access public files
            $query->where('access_level', 'public');
        } else {
            // Logged in users can access public and registered files
            $query->where(function ($q) {
                $q->where('access_level', 'public')
                    ->orWhere('access_level', 'registered');
            });
        }

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by file type
        if ($request->file_type) {
            $query->where('file_type', $request->file_type);
        }

        $digitalFiles = $query->latest('published_at')->paginate(12)->withQueryString();

        return view('public.digital-library.index', compact('digitalFiles'));
    }

    /**
     * Display the specified digital file for public.
     */
    public function publicShow(DigitalFile $digitalFile)
    {
        // Check access
        if (!$digitalFile->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Increment view count
        $digitalFile->incrementViewCount();

        $digitalFile->load(['collection']);

        return view('public.digital-library.show', ['file' => $digitalFile]);
    }

    /**
     * Preview the specified digital file for public.
     */
    public function publicPreview(DigitalFile $digitalFile)
    {
        // Check access
        if (!$digitalFile->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Check if file is active and published
        if (!$digitalFile->is_active || !$digitalFile->published_at || $digitalFile->published_at->isFuture()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if file exists
        if (!$digitalFile->fileExists()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Only PDF and images can be previewed
        if (!$digitalFile->isPdf() && !$digitalFile->isImage()) {
            return redirect()
                ->route('digital-library.show', $digitalFile)
                ->with('info', 'Tipe file ini tidak dapat dipreview, silakan download.');
        }

        // Increment view count
        $digitalFile->incrementViewCount();

        return view('public.digital-library.preview', ['file' => $digitalFile]);
    }

    /**
     * Download the specified digital file for public.
     */
    public function publicDownload(DigitalFile $digitalFile)
    {
        // Check access
        if (!$digitalFile->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Check if file is active and published
        if (!$digitalFile->is_active || !$digitalFile->published_at || $digitalFile->published_at->isFuture()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if file exists
        if (!$digitalFile->fileExists()) {
            abort(404, 'File tidak ditemukan.');
        }

        // Increment download count
        $digitalFile->incrementDownloadCount();

        return Storage::download($digitalFile->file_path, $digitalFile->file_name);
    }
}
