<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InRepository;
use App\Services\InRepositoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InRepositoryController extends Controller
{
    protected InRepositoryService $service;

    public function __construct(InRepositoryService $service)
    {
        $this->service = $service;
        $this->middleware('permission:repositories.view');
    }

    /**
     * Display a listing of the repositories.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'document_type', 'year', 'access_level', 'search']);
        $perPage = $request->get('per_page', 15);

        $repositories = $this->service->paginate($perPage, $filters);
        $statistics = $this->service->getStatistics();

        return view('admin.repositories.index', compact('repositories', 'statistics'));
    }

    /**
     * Show the form for creating a new repository.
     */
    public function create(): View
    {
        return view('admin.repositories.create');
    }

    /**
     * Store a newly created repository.
     */
    public function store(Request $request)
    {
        $this->middleware('permission:repositories.create');

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
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'keywords' => 'nullable|string|max:500',
            'access_level' => 'required|in:public,registered,campus_only,restricted',
            'is_downloadable' => 'boolean',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');

        $repository = $this->service->create($validated, $file);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil disubmit dan menunggu moderasi.');
    }

    /**
     * Display the specified repository.
     */
    public function show(InRepository $repository): View
    {
        $repository = $this->service->getWithRelations($repository->id, [
            'member',
            'branch',
            'classification',
            'approvedBy',
            'rejectedBy',
        ]);

        return view('admin.repositories.show', compact('repository'));
    }

    /**
     * Show the form for editing the specified repository.
     */
    public function edit(InRepository $repository): View
    {
        $this->middleware('permission:repositories.edit');

        return view('admin.repositories.edit', compact('repository'));
    }

    /**
     * Update the specified repository.
     */
    public function update(Request $request, InRepository $repository)
    {
        $this->middleware('permission:repositories.edit');

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
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'keywords' => 'nullable|string|max:500',
            'access_level' => 'required|in:public,registered,campus_only,restricted',
            'is_downloadable' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file');

        $repository = $this->service->update($repository, $validated, $file);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil diperbarui.');
    }

    /**
     * Remove the specified repository.
     */
    public function destroy(InRepository $repository)
    {
        $this->middleware('permission:repositories.delete');

        $this->service->delete($repository);

        return redirect()
            ->route('repositories.index')
            ->with('success', 'Repository berhasil dihapus.');
    }

    /**
     * Approve the repository.
     */
    public function approve(InRepository $repository)
    {
        $this->middleware('permission:repositories.moderate');

        $repository = $this->service->approve($repository);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil disetujui.');
    }

    /**
     * Reject the repository.
     */
    public function reject(Request $request, InRepository $repository)
    {
        $this->middleware('permission:repositories.moderate');

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $repository = $this->service->reject($repository, $validated['reason']);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil ditolak.');
    }

    /**
     * Publish the repository.
     */
    public function publish(InRepository $repository)
    {
        $this->middleware('permission:repositories.moderate');

        $repository = $this->service->publish($repository);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil diterbitkan.');
    }

    /**
     * Archive the repository.
     */
    public function archive(InRepository $repository)
    {
        $this->middleware('permission:repositories.moderate');

        $repository = $this->service->archive($repository);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'Repository berhasil diarsipkan.');
    }

    /**
     * Assign DOI to the repository.
     */
    public function assignDoi(Request $request, InRepository $repository)
    {
        $this->middleware('permission:repositories.edit');

        $validated = $request->validate([
            'doi' => 'required|string|max:255|unique:in_repositories,doi,' . $repository->id,
        ]);

        $repository = $this->service->assignDoi($repository, $validated['doi']);

        return redirect()
            ->route('repositories.show', $repository)
            ->with('success', 'DOI berhasil ditetapkan.');
    }
}
