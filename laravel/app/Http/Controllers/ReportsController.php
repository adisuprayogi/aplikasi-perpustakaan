<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportsController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    /**
     * Display the dashboard with statistics.
     */
    public function dashboard(Request $request): View
    {
        $branchId = $request->user()->branch_id;

        $stats = $this->reportService->getDashboardStats($branchId);
        $circulationTrends = $this->reportService->getCirculationTrends(12, $branchId);
        $collectionStats = $this->reportService->getCollectionStats($branchId);
        $memberStats = $this->reportService->getMemberStats($branchId);

        return view('admin.reports.dashboard', compact('stats', 'circulationTrends', 'collectionStats', 'memberStats'));
    }

    /**
     * Display loan report page.
     */
    public function loans(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $stats = $this->reportService->getLoanStats($startDate, $endDate, $branchId);

        return view('admin.reports.loans', compact('stats', 'startDate', 'endDate'));
    }

    /**
     * Export loan report to CSV.
     */
    public function exportLoansCsv(Request $request): BinaryFileResponse
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $filepath = $this->reportService->exportLoanReportCsv($startDate, $endDate, $branchId);
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Display overdue report page.
     */
    public function overdue(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $stats = $this->reportService->getOverdueReport($startDate, $endDate, $branchId);

        return view('admin.reports.overdue', compact('stats', 'startDate', 'endDate'));
    }

    /**
     * Export overdue report to CSV.
     */
    public function exportOverdueCsv(Request $request): BinaryFileResponse
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $filepath = $this->reportService->exportOverdueReportCsv($startDate, $endDate, $branchId);
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Display fine report page.
     */
    public function fines(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $stats = $this->reportService->getFineReport($startDate, $endDate, $branchId);

        return view('admin.reports.fines', compact('stats', 'startDate', 'endDate'));
    }

    /**
     * Export fine report to CSV.
     */
    public function exportFinesCsv(Request $request): BinaryFileResponse
    {
        $branchId = $request->user()->branch_id;
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $filepath = $this->reportService->exportFineReportCsv($startDate, $endDate, $branchId);
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Display collection report page.
     */
    public function collections(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $stats = $this->reportService->getCollectionStats($branchId);

        return view('admin.reports.collections', compact('stats'));
    }

    /**
     * Export collection report to CSV.
     */
    public function exportCollectionsCsv(Request $request): BinaryFileResponse
    {
        $branchId = $request->user()->branch_id;

        $filepath = $this->reportService->exportCollectionReportCsv($branchId);
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Display member report page.
     */
    public function members(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $stats = $this->reportService->getMemberStats($branchId);

        return view('admin.reports.members', compact('stats'));
    }

    /**
     * Export member report to CSV.
     */
    public function exportMembersCsv(Request $request): BinaryFileResponse
    {
        $branchId = $request->user()->branch_id;

        $filepath = $this->reportService->exportMemberReportCsv($branchId);
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Display branch comparison report page.
     */
    public function branches(Request $request): View
    {
        $comparison = $this->reportService->getBranchComparisonReport();

        return view('admin.reports.branches', compact('comparison'));
    }

    /**
     * Export branch comparison report to CSV.
     */
    public function exportBranchesCsv(Request $request): BinaryFileResponse
    {
        $filepath = $this->reportService->exportBranchComparisonReportCsv();
        $filename = basename($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}
