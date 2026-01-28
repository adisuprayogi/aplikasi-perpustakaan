<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    /**
     * Display settings page.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'library_info');

        $groups = [
            'library_info' => 'Informasi Perpustakaan',
            'loan' => 'Pengaturan Peminjaman',
            'fine' => 'Pengaturan Denda',
            'reservation' => 'Pengaturan Reservasi',
            'email' => 'Pengaturan Email',
            'opac' => 'Pengaturan OPAC',
        ];

        $settings = $this->settingsService->all();

        return view('admin.settings.index', compact('activeTab', 'groups', 'settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'library_name' => 'nullable|string|max:255',
            'library_address' => 'nullable|string',
            'library_phone' => 'nullable|string|max:50',
            'library_email' => 'nullable|email|max:255',
            'library_open_hours' => 'nullable|string|max:255',
            'loan_default_period' => 'nullable|integer|min:1|max:365',
            'loan_max_renewal' => 'nullable|integer|min:0|max:10',
            'loan_grace_period' => 'nullable|integer|min:0|max:30',
            'loan_auto_calc_fine' => 'nullable|boolean',
            'fine_daily_rate' => 'nullable|integer|min:0|max:1000000',
            'fine_max_fine' => 'nullable|integer|min:0|max:10000000',
            'fine_currency' => 'nullable|string|max:10',
            'fine_exclude_holidays' => 'nullable|boolean',
            'reservation_max_per_member' => 'nullable|integer|min:1|max:50',
            'reservation_expiry_days' => 'nullable|integer|min:1|max:30',
            'reservation_allow_queue' => 'nullable|boolean',
            'email_from_address' => 'nullable|email|max:255',
            'email_from_name' => 'nullable|string|max:255',
            'email_notifications_enabled' => 'nullable|boolean',
            'opac_items_per_page' => 'nullable|integer|min:5|max:100',
            'opac_enable_search' => 'nullable|boolean',
            'opac_show_cover' => 'nullable|boolean',
        ]);

        // Library Information
        $this->updateSetting('library.name', $validated['library_name'] ?? null);
        $this->updateSetting('library.address', $validated['library_address'] ?? null);
        $this->updateSetting('library.phone', $validated['library_phone'] ?? null);
        $this->updateSetting('library.email', $validated['library_email'] ?? null);
        $this->updateSetting('library.open_hours', $validated['library_open_hours'] ?? null);

        // Loan Settings
        $this->updateSetting('loan.default_period', $validated['loan_default_period'] ?? 14, 'integer');
        $this->updateSetting('loan.max_renewal', $validated['loan_max_renewal'] ?? 2, 'integer');
        $this->updateSetting('loan.grace_period', $validated['loan_grace_period'] ?? 0, 'integer');
        $this->updateSetting('loan.auto_calc_fine', $validated['loan_auto_calc_fine'] ?? true, 'boolean');

        // Fine Settings
        $this->updateSetting('fine.daily_rate', $validated['fine_daily_rate'] ?? 1000, 'integer');
        $this->updateSetting('fine.max_fine', $validated['fine_max_fine'] ?? 50000, 'integer');
        $this->updateSetting('fine.currency', $validated['fine_currency'] ?? 'IDR', 'string');
        $this->updateSetting('fine.exclude_holidays', $validated['fine_exclude_holidays'] ?? true, 'boolean');

        // Reservation Settings
        $this->updateSetting('reservation.max_per_member', $validated['reservation_max_per_member'] ?? 5, 'integer');
        $this->updateSetting('reservation.expiry_days', $validated['reservation_expiry_days'] ?? 2, 'integer');
        $this->updateSetting('reservation.allow_queue', $validated['reservation_allow_queue'] ?? true, 'boolean');

        // Email Settings
        $this->updateSetting('email.from_address', $validated['email_from_address'] ?? null);
        $this->updateSetting('email.from_name', $validated['email_from_name'] ?? null);
        $this->updateSetting('email.notifications_enabled', $validated['email_notifications_enabled'] ?? true, 'boolean');

        // OPAC Settings
        $this->updateSetting('opac.items_per_page', $validated['opac_items_per_page'] ?? 12, 'integer');
        $this->updateSetting('opac.enable_search', $validated['opac_enable_search'] ?? true, 'boolean');
        $this->updateSetting('opac.show_cover', $validated['opac_show_cover'] ?? true, 'boolean');

        return redirect()
            ->route('settings.index', ['tab' => $request->input('current_tab', 'library_info')])
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Reset settings to default values.
     */
    public function reset(Request $request): RedirectResponse
    {
        $this->settingsService->seedDefaults();

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan telah direset ke nilai default.');
    }

    /**
     * Helper to update a setting.
     */
    private function updateSetting(string $key, mixed $value, string $type = 'string'): void
    {
        if ($value !== null) {
            // Determine group from key
            $group = explode('.', $key)[0];

            $this->settingsService->set(
                $key,
                $value,
                $type,
                $group
            );
        }
    }
}
