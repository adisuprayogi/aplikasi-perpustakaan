<?php

namespace App\Http\Controllers;

use App\Models\InRepository;
use App\Services\InRepositoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RepositoryController extends Controller
{
    protected InRepositoryService $service;

    public function __construct(InRepositoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of published repositories.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['document_type', 'year', 'search']);
        $perPage = $request->get('per_page', 15);

        $repositories = $this->service->paginatePublished($perPage, $filters);

        // Get filter options
        $recentRepositories = $this->service->getRecent(5);
        $popularRepositories = $this->service->getPopular(5);

        return view('repository.index', compact(
            'repositories',
            'recentRepositories',
            'popularRepositories'
        ));
    }

    /**
     * Display the specified repository.
     */
    public function show(string $slug): View
    {
        $repository = $this->service->repository->findBySlugOrFail($slug);

        // Check access
        if (!$this->service->canAccess($repository, Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke repository ini.');
        }

        // Track view
        $this->service->trackView($repository);

        return view('repository.show', compact('repository'));
    }

    /**
     * Download the repository file.
     */
    public function download(string $slug): StreamedResponse
    {
        $repository = $this->service->repository->findBySlugOrFail($slug);

        // Check download permission
        if (!$this->service->canDownload($repository, Auth::user())) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh file ini.');
        }

        // Track download
        $this->service->trackDownload($repository);

        $filePath = storage_path('app/public/' . $repository->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $repository->file_name);
    }

    /**
     * Search repositories.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        $perPage = $request->get('per_page', 15);

        if (empty($query)) {
            return redirect()->route('repository.index');
        }

        $repositories = $this->service->search($query, $perPage);

        return view('repository.search', compact('repositories', 'query'));
    }

    /**
     * Show the submission form for members.
     */
    public function create(): View
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('repository.create');
    }

    /**
     * Store a newly submitted repository.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login untuk submit repository.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'language' => 'required|string|max:10',
            'author_name' => 'required|string|max:255',
            'author_nim' => 'nullable|string|max:50',
            'author_email' => 'nullable|email|max:255',
            'advisor_name' => 'nullable|string|max:255',
            'co_advisor_name' => 'nullable|string|max:255',
            'document_type' => 'required|in:undergraduate_thesis,masters_thesis,doctoral_dissertation,research_paper,journal_article,conference_paper,book_chapter,technical_report,other',
            'department' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'program_study' => 'nullable|string|max:255',
            'classification_id' => 'nullable|exists:classifications,id',
            'keywords' => 'nullable|string|max:500',
            'access_level' => 'required|in:public,registered,campus_only,restricted',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file');

        // Set member from authenticated user
        $member = Auth::user()->member;
        if (!$member) {
            return redirect()->back()
                ->with('error', 'Hanya anggota yang dapat submit repository.');
        }

        $validated['member_id'] = $member->id;
        $validated['branch_id'] = $member->branch_id;

        $repository = $this->service->create($validated, $file);

        return redirect()
            ->route('my-repositories')
            ->with('success', 'Repository berhasil disubmit dan menunggu moderasi.');
    }

    /**
     * Display user's submitted repositories.
     */
    public function myRepositories(): View
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member = Auth::user()->member;

        if (!$member) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki profil anggota.');
        }

        $repositories = $this->service->getByMember($member->id);

        return view('repository.my-repositories', compact('repositories'));
    }
}
