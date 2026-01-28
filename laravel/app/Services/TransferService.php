<?php

namespace App\Services;

use App\Models\ItemTransfer;
use App\Models\CollectionItem;
use App\Repositories\TransferRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class TransferService
{
    protected TransferRepository $transferRepository;
    protected ItemRepository $itemRepository;

    public function __construct(
        TransferRepository $transferRepository,
        ItemRepository $itemRepository
    ) {
        $this->transferRepository = $transferRepository;
        $this->itemRepository = $itemRepository;
    }

    /**
     * Create a new transfer request.
     */
    public function createRequest(array $data): ItemTransfer
    {
        // Validate item exists and is available
        $item = $this->itemRepository->find($data['item_id']);
        if (!$item) {
            throw new Exception('Item not found.');
        }

        // Check if item has pending or shipped transfer
        if ($this->transferRepository->hasPendingTransfer($data['item_id'])) {
            throw new Exception('Item already has a pending transfer request.');
        }

        if ($this->transferRepository->hasShippedTransfer($data['item_id'])) {
            throw new Exception('Item is currently being transferred.');
        }

        // Get from branch
        $fromBranchId = $item->branch_id;
        $toBranchId = $data['to_branch_id'];

        // Validate branches are different
        if ($fromBranchId == $toBranchId) {
            throw new Exception('Cannot transfer item within the same branch.');
        }

        // Validate from branch matches item location
        if (isset($data['from_branch_id']) && $data['from_branch_id'] != $fromBranchId) {
            throw new Exception('Item is not located at the specified source branch.');
        }

        DB::beginTransaction();
        try {
            $transfer = $this->transferRepository->create([
                'item_id' => $data['item_id'],
                'from_branch_id' => $fromBranchId,
                'to_branch_id' => $toBranchId,
                'requested_by' => Auth::id(),
                'requested_at' => now(),
                'status' => ItemTransfer::STATUS_PENDING,
                'notes' => $data['notes'] ?? null,
                'metadata' => $data['metadata'] ?? null,
            ]);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Ship a transfer.
     */
    public function shipTransfer(int $transferId): ItemTransfer
    {
        $transfer = $this->transferRepository->find($transferId);
        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        if (!$transfer->isPending()) {
            throw new Exception('Only pending transfers can be shipped.');
        }

        DB::beginTransaction();
        try {
            $transfer = $this->transferRepository->update($transfer, [
                'status' => ItemTransfer::STATUS_SHIPPED,
                'shipped_by' => Auth::id(),
                'shipped_at' => now(),
            ]);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Receive a transfer.
     */
    public function receiveTransfer(int $transferId, ?string $condition = null): ItemTransfer
    {
        $transfer = $this->transferRepository->find($transferId);
        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        if (!$transfer->isShipped()) {
            throw new Exception('Only shipped transfers can be received.');
        }

        DB::beginTransaction();
        try {
            // Update item location
            $this->itemRepository->update($transfer->item_id, [
                'branch_id' => $transfer->to_branch_id,
                'status' => 'available',
            ]);

            // Update metadata with condition
            $metadata = $transfer->metadata ?? [];
            if ($condition) {
                $metadata['received_condition'] = $condition;
            }

            // Update transfer status
            $transfer = $this->transferRepository->update($transfer, [
                'status' => ItemTransfer::STATUS_RECEIVED,
                'received_by' => Auth::id(),
                'received_at' => now(),
                'metadata' => $metadata,
            ]);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel a transfer.
     */
    public function cancelTransfer(int $transferId, ?string $reason = null): ItemTransfer
    {
        $transfer = $this->transferRepository->find($transferId);
        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        if (!$transfer->isPending()) {
            throw new Exception('Only pending transfers can be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Update metadata with reason
            $metadata = $transfer->metadata ?? [];
            if ($reason) {
                $metadata['cancellation_reason'] = $reason;
            }

            $transfer = $this->transferRepository->update($transfer, [
                'status' => ItemTransfer::STATUS_CANCELLED,
                'metadata' => $metadata,
            ]);

            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get transfer statistics.
     */
    public function getStatistics(?int $branchId = null): array
    {
        return [
            'pending_transfers' => $this->transferRepository->getPendingCount($branchId),
            'shipped_transfers' => $this->transferRepository->getShippedCount($branchId),
        ];
    }

    /**
     * Get paginated transfers list.
     */
    public function getTransfers(array $filters = [])
    {
        return $this->transferRepository->listWithFilters($filters);
    }

    /**
     * Get transfers for current branch (as source or destination).
     */
    public function getTransfersForBranch(int $branchId, array $filters = []): array
    {
        $filters['branch_id'] = $branchId;
        return $this->transferRepository->listWithFilters($filters)->toArray();
    }

    /**
     * Get incoming transfers for a branch.
     */
    public function getIncomingTransfers(int $branchId, array $filters = []): array
    {
        $filters['to_branch_id'] = $branchId;
        return $this->transferRepository->listWithFilters($filters)->toArray();
    }

    /**
     * Get outgoing transfers for a branch.
     */
    public function getOutgoingTransfers(int $branchId, array $filters = []): array
    {
        $filters['from_branch_id'] = $branchId;
        return $this->transferRepository->listWithFilters($filters)->toArray();
    }
}
