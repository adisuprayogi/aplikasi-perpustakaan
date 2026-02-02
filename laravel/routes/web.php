<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanRuleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DigitalFileController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\Admin\InRepositoryController;
use Illuminate\Support\Facades\Route;

// Public OPAC Routes (no authentication required)
Route::get('/opac', [SearchController::class, 'index'])->name('opac.index');
Route::get('/opac/search', [SearchController::class, 'search'])->name('opac.search')->middleware('throttle:search');
Route::get('/opac/advanced', [SearchController::class, 'advanced'])->name('opac.advanced');
Route::get('/opac/{id}', [SearchController::class, 'show'])->name('opac.show');
Route::get('/api/autocomplete', [SearchController::class, 'autocomplete'])->name('api.autocomplete')->middleware('throttle:search');

// Public Digital Library Routes
Route::prefix('digital-library')->name('digital-library.')->group(function () {
    Route::get('/', [DigitalFileController::class, 'publicIndex'])->name('index');
    Route::get('/{digitalFile}/download', [DigitalFileController::class, 'publicDownload'])->name('download')->middleware('throttle:download');
    Route::get('/{digitalFile}/preview', [DigitalFileController::class, 'publicPreview'])->name('preview')->middleware('throttle:download');
    Route::get('/{digitalFile}', [DigitalFileController::class, 'publicShow'])->name('show');
});

// Public Institutional Repository Routes
Route::prefix('repository')->name('repository.')->group(function () {
    Route::get('/', [RepositoryController::class, 'index'])->name('index');
    Route::get('/search', [RepositoryController::class, 'search'])->name('search')->middleware('throttle:search');
    Route::get('/{slug}', [RepositoryController::class, 'show'])->name('show');
    Route::get('/{slug}/download', [RepositoryController::class, 'download'])->name('download')->middleware('throttle:download');
    Route::get('/create', [RepositoryController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [RepositoryController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/my-repositories', [RepositoryController::class, 'myRepositories'])->name('my')->middleware('auth');
});

// Redirect root to OPAC (public) or dashboard (authenticated)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('opac.index');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Branch Management
    Route::resource('branches', BranchController::class);
    Route::post('branches/{branch}/restore', [BranchController::class, 'restore'])
        ->name('branches.restore');

    // Member Management
    Route::resource('members', MemberController::class);
    Route::post('members/{member}/renew', [MemberController::class, 'renew'])
        ->name('members.renew');
    Route::post('members/{member}/suspend', [MemberController::class, 'suspend'])
        ->name('members.suspend');

    // Collection Management
    Route::resource('collections', CollectionController::class);
    Route::post('collections/{collection}/cover', [CollectionController::class, 'uploadCover'])->name('collections.cover.upload');
    Route::delete('collections/{collection}/cover', [CollectionController::class, 'removeCover'])->name('collections.cover.remove');
    Route::get('collections/labels', [CollectionController::class, 'labels'])->name('collections.labels');
    Route::get('collection-items/{item}/barcode', [CollectionController::class, 'generateBarcode'])->name('collection-items.barcode');
    Route::get('collection-items/{item}/qrcode', [CollectionController::class, 'generateQrCode'])->name('collection-items.qrcode');
    Route::get('collection-items/{item}/label', [CollectionController::class, 'generateLabel'])->name('collection-items.label');

    // Loan Rules
    Route::resource('loan-rules', LoanRuleController::class)->parameters([
        'loan-rules' => 'loanRule'
    ]);

    // Loans / Circulation
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('loans/{loan}/return', [LoanController::class, 'return'])
        ->name('loans.return');
    Route::post('loans/{loan}/renew', [LoanController::class, 'renew'])
        ->name('loans.renew');
    Route::get('api/search/member', [LoanController::class, 'searchMember'])
        ->name('api.search.member');
    Route::get('api/search/item', [LoanController::class, 'searchItem'])
        ->name('api.search.item');

    // Fine Payments
    Route::get('loans/{loan}/fine/payment', [FineController::class, 'create'])
        ->name('fines.create');
    Route::post('loans/{loan}/fine/payment', [FineController::class, 'store'])
        ->name('fines.store');
    Route::get('loans/{loan}/fine/history', [FineController::class, 'history'])
        ->name('fines.history');
    Route::post('loans/{loan}/fine/waive', [FineController::class, 'waive'])
        ->name('fines.waive');
    Route::get('members/{member}/fines', [FineController::class, 'memberFines'])
        ->name('fines.member');

    // Reservations
    Route::resource('reservations', ReservationController::class)->except(['edit', 'update']);
    Route::post('reservations/{reservation}/mark-ready', [ReservationController::class, 'markAsReady'])
        ->name('reservations.mark-ready');
    Route::post('reservations/{reservation}/fulfill', [ReservationController::class, 'fulfill'])
        ->name('reservations.fulfill');
    Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
    Route::get('my-reservations', [ReservationController::class, 'myReservations'])
        ->name('reservations.my');
    Route::post('my-reservations/{reservation}/cancel', [ReservationController::class, 'cancelMyReservation'])
        ->name('reservations.cancel-my');
    Route::get('api/search/reservation-member', [ReservationController::class, 'searchMember'])
        ->name('api.search.reservation-member');
    Route::get('api/search/reservation-item', [ReservationController::class, 'searchItem'])
        ->name('api.search.reservation-item');
    Route::get('api/available-items', [ReservationController::class, 'getAvailableItems'])
        ->name('api.available-items');

    // Notifications
    Route::prefix('api/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/reset', [SettingsController::class, 'reset'])->name('settings.reset')->can('manage settings');
    });

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportsController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/loans', [ReportsController::class, 'loans'])->name('reports.loans');
        Route::get('/loans/export', [ReportsController::class, 'exportLoansCsv'])->name('reports.loans.export');
        Route::get('/overdue', [ReportsController::class, 'overdue'])->name('reports.overdue');
        Route::get('/overdue/export', [ReportsController::class, 'exportOverdueCsv'])->name('reports.overdue.export');
        Route::get('/fines', [ReportsController::class, 'fines'])->name('reports.fines');
        Route::get('/fines/export', [ReportsController::class, 'exportFinesCsv'])->name('reports.fines.export');
        Route::get('/collections', [ReportsController::class, 'collections'])->name('reports.collections');
        Route::get('/collections/export', [ReportsController::class, 'exportCollectionsCsv'])->name('reports.collections.export');
        Route::get('/members', [ReportsController::class, 'members'])->name('reports.members');
        Route::get('/members/export', [ReportsController::class, 'exportMembersCsv'])->name('reports.members.export');
        Route::get('/branches', [ReportsController::class, 'branches'])->name('reports.branches');
        Route::get('/branches/export', [ReportsController::class, 'exportBranchesCsv'])->name('reports.branches.export');
    });

    // Transfers
    Route::prefix('transfers')->group(function () {
        Route::get('/', [TransferController::class, 'index'])->name('transfers.index');
        Route::get('/create', [TransferController::class, 'create'])->name('transfers.create');
        Route::post('/', [TransferController::class, 'store'])->name('transfers.store');
        Route::get('/{id}', [TransferController::class, 'show'])->name('transfers.show');
        Route::post('/{id}/ship', [TransferController::class, 'ship'])->name('transfers.ship');
        Route::get('/{id}/receive', [TransferController::class, 'receiveForm'])->name('transfers.receive-form');
        Route::post('/{id}/receive', [TransferController::class, 'receive'])->name('transfers.receive');
        Route::get('/{id}/cancel', [TransferController::class, 'cancelForm'])->name('transfers.cancel-form');
        Route::post('/{id}/cancel', [TransferController::class, 'cancel'])->name('transfers.cancel');
        Route::get('/search-items', [TransferController::class, 'searchItems'])->name('transfers.search-items');
    });

    // Digital Files Management
    Route::prefix('digital-files')->name('digital-files.')->group(function () {
        Route::get('/', [DigitalFileController::class, 'index'])->name('index');
        Route::get('/create', [DigitalFileController::class, 'create'])->name('create');
        Route::post('/', [DigitalFileController::class, 'store'])->name('store');
        Route::get('/{digitalFile}/download', [DigitalFileController::class, 'download'])->name('download');
        Route::get('/{digitalFile}/preview', [DigitalFileController::class, 'preview'])->name('preview');
        Route::get('/{digitalFile}/edit', [DigitalFileController::class, 'edit'])->name('edit');
        Route::put('/{digitalFile}', [DigitalFileController::class, 'update'])->name('update');
        Route::delete('/{digitalFile}', [DigitalFileController::class, 'destroy'])->name('destroy');
        Route::get('/{digitalFile}', [DigitalFileController::class, 'show'])->name('show');
    });

    // Institutional Repository Management
    Route::prefix('repositories')->name('repositories.')->group(function () {
        Route::get('/', [InRepositoryController::class, 'index'])->name('index');
        Route::get('/create', [InRepositoryController::class, 'create'])->name('create');
        Route::post('/', [InRepositoryController::class, 'store'])->name('store');
        Route::get('/{inRepository}', [InRepositoryController::class, 'show'])->name('show');
        Route::get('/{inRepository}/edit', [InRepositoryController::class, 'edit'])->name('edit');
        Route::put('/{inRepository}', [InRepositoryController::class, 'update'])->name('update');
        Route::delete('/{inRepository}', [InRepositoryController::class, 'destroy'])->name('destroy');
        Route::post('/{inRepository}/approve', [InRepositoryController::class, 'approve'])->name('approve');
        Route::post('/{inRepository}/reject', [InRepositoryController::class, 'reject'])->name('reject');
        Route::post('/{inRepository}/publish', [InRepositoryController::class, 'publish'])->name('publish');
        Route::post('/{inRepository}/archive', [InRepositoryController::class, 'archive'])->name('archive');
        Route::post('/{inRepository}/doi', [InRepositoryController::class, 'assignDoi'])->name('assign-doi');
    });
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
