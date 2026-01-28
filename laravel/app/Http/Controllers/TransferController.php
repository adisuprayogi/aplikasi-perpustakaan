<?php

namespace App\Http\Controllers;

use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransferController extends Controller
{
    protected TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Display a listing of transfers.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->input('status'),
            'from_branch_id' => $request->input('from_branch_id'),
            'to_branch_id' => $request->input('to_branch_id'),
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 15),
        ];

        // Get current user's branch
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            $filters['branch_id'] = $user->branch_id;
        }

        $transfers = $this->transferService->getTransfers($filters);
        $statistics = $this->transferService->getStatistics($user->branch_id);

        $branches = \App\Models\Branch::all();

        return view('admin.transfers.index', compact('transfers', 'statistics', 'branches', 'filters'));
    }

    /**
     * Show the form for creating a new transfer.
     */
    public function create(): View
    {
        $branches = \App\Models\Branch::all();

        // Get current user's branch as default source
        $user = auth()->user();
        $fromBranch = $user->branch_id ? \App\Models\Branch::find($user->branch_id) : null;

        return view('admin.transfers.create', compact('branches', 'fromBranch'));
    }

    /**
     * Store a newly created transfer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:collection_items,id',
            'to_branch_id' => 'required|exists:branches,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->transferService->createRequest($validated);

            return redirect()
                ->route('transfers.index')
                ->with('success', 'Transfer request created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified transfer.
     */
    public function show(int $id): View
    {
        $transfer = $this->transferService->transferRepository->find($id);

        if (!$transfer) {
            abort(404);
        }

        // Get transfer history for the item
        $itemTransfers = $this->transferService->transferRepository->getByItemId($transfer->item_id);

        return view('admin.transfers.show', compact('transfer', 'itemTransfers'));
    }

    /**
     * Ship a transfer.
     */
    public function ship(int $id): RedirectResponse
    {
        try {
            $this->transferService->shipTransfer($id);

            return back()->with('success', 'Transfer marked as shipped.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for receiving a transfer.
     */
    public function receiveForm(int $id): View
    {
        $transfer = $this->transferService->transferRepository->find($id);

        if (!$transfer) {
            abort(404);
        }

        return view('admin.transfers.receive', compact('transfer'));
    }

    /**
     * Receive a transfer.
     */
    public function receive(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'condition' => 'nullable|string|max:100',
        ]);

        try {
            $this->transferService->receiveTransfer($id, $validated['condition'] ?? null);

            return redirect()
                ->route('transfers.show', $id)
                ->with('success', 'Transfer received successfully. Item location updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for cancelling a transfer.
     */
    public function cancelForm(int $id): View
    {
        $transfer = $this->transferService->transferRepository->find($id);

        if (!$transfer) {
            abort(404);
        }

        return view('admin.transfers.cancel', compact('transfer'));
    }

    /**
     * Cancel a transfer.
     */
    public function cancel(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->transferService->cancelTransfer($id, $validated['reason'] ?? null);

            return redirect()
                ->route('transfers.show', $id)
                ->with('success', 'Transfer cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Search items for transfer.
     */
    public function searchItems(Request $request): View
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'to_branch_id' => 'required|exists:branches,id',
        ]);

        $item = \App\Models\CollectionItem::with('collection')
            ->where('barcode', $validated['barcode'])
            ->first();

        $toBranch = \App\Models\Branch::find($validated['to_branch_id']);

        return view('admin.transfers.partials.item-result', compact('item', 'toBranch'));
    }
}
