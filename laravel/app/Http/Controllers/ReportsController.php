<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view('admin.reports.dashboard', compact('stats', 'circulationTrends'));
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
     * Display collection report page.
     */
    public function collections(Request $request): View
    {
        $branchId = $request->user()->branch_id;
        $stats = $this->reportService->getCollectionStats($branchId);

        return view('admin.reports.collections', compact('stats'));
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
}
