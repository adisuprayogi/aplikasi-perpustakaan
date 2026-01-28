<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Cache duration in seconds (1 hour).
     */
    private const CACHE_DURATION = 3600;

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @param int|null $branchId
     * @return mixed
     */
    public function get(string $key, mixed $default = null, ?int $branchId = null): mixed
    {
        $cacheKey = $this->getCacheKey($key, $branchId);

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $branchId, $default) {
            $query = Setting::where('key', $key);

            if ($branchId !== null) {
                // First try to get branch-specific setting
                $branchSetting = (clone $query)->where('branch_id', $branchId)->first();
                if ($branchSetting) {
                    return $this->castValue($branchSetting->value, $branchSetting->type);
                }
            }

            // Fall back to global setting
            $setting = $query->whereNull('branch_id')->first();

            if (!$setting) {
                return $default;
            }

            return $this->castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @param int|null $branchId
     * @param string|null $description
     * @return Setting
     */
    public function set(
        string $key,
        mixed $value,
        string $type = 'string',
        string $group = 'general',
        ?int $branchId = null,
        ?string $description = null
    ): Setting {
        $setting = Setting::updateOrCreate(
            [
                'key' => $key,
                'branch_id' => $branchId,
            ],
            [
                'value' => $this->prepareValue($value, $type),
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );

        // Clear cache for this key
        $this->clearCache($key, $branchId);

        return $setting;
    }

    /**
     * Get all settings by group.
     *
     * @param string $group
     * @param int|null $branchId
     * @return array
     */
    public function getGroup(string $group, ?int $branchId = null): array
    {
        $cacheKey = "settings_group_{$group}" . ($branchId ? "_branch_{$branchId}" : '');

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($group, $branchId) {
            $settings = Setting::group($group)
                ->where(function ($query) use ($branchId) {
                    if ($branchId) {
                        $query->whereNull('branch_id')
                            ->orWhere('branch_id', $branchId);
                    } else {
                        $query->whereNull('branch_id');
                    }
                })
                ->get()
                ->keyBy('key');

            // If branch is specified, merge with global settings
            if ($branchId) {
                $globalSettings = Setting::group($group)
                    ->global()
                    ->get()
                    ->keyBy('key');

                $settings = $globalSettings->merge($settings);
            }

            return $settings->map(function ($setting) {
                return $this->castValue($setting->value, $setting->type);
            })->toArray();
        });
    }

    /**
     * Get all settings as array.
     *
     * @param int|null $branchId
     * @return array
     */
    public function all(?int $branchId = null): array
    {
        $query = Setting::query();

        if ($branchId) {
            $settings = $query->whereNull('branch_id')
                ->orWhere('branch_id', $branchId)
                ->get()
                ->keyBy('key');

            // Prioritize branch settings over global
            $branchSettings = Setting::where('branch_id', $branchId)
                ->get()
                ->keyBy('key');

            $settings = $settings->merge($branchSettings);
        } else {
            $settings = $query->global()->get()->keyBy('key');
        }

        return $settings->map(function ($setting) {
            return $this->castValue($setting->value, $setting->type);
        })->toArray();
    }

    /**
     * Delete a setting.
     *
     * @param string $key
     * @param int|null $branchId
     * @return bool
     */
    public function delete(string $key, ?int $branchId = null): bool
    {
        $query = Setting::where('key', $key);

        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        } else {
            $query->whereNull('branch_id');
        }

        $deleted = $query->delete();

        if ($deleted) {
            $this->clearCache($key, $branchId);
        }

        return $deleted > 0;
    }

    /**
     * Clear all settings cache.
     */
    public function clearAllCache(): void
    {
        Cache::forget('settings_all');
        Cache::forget('settings_all_global');
    }

    /**
     * Clear cache for a specific key.
     *
     * @param string $key
     * @param int|null $branchId
     */
    private function clearCache(string $key, ?int $branchId = null): void
    {
        Cache::forget($this->getCacheKey($key, $branchId));
        Cache::forget('settings_all');
        Cache::forget('settings_all_global');

        // Clear group cache
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            Cache::forget("settings_group_{$setting->group}");
            if ($branchId) {
                Cache::forget("settings_group_{$setting->group}_branch_{$branchId}");
            }
        }
    }

    /**
     * Get cache key for a setting.
     *
     * @param string $key
     * @param int|null $branchId
     * @return string
     */
    private function getCacheKey(string $key, ?int $branchId = null): string
    {
        return "setting_{$key}" . ($branchId ? "_branch_{$branchId}" : '');
    }

    /**
     * Cast value based on type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $value,
            'number', 'float', 'double' => (float) $value,
            'array', 'json' => is_array($value) ? $value : json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Prepare value for storage based on type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private function prepareValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'array', 'json' => is_string($value) ? $value : json_encode($value),
            'boolean', 'bool' => $value ? '1' : '0',
            'integer', 'int' => (string) $value,
            'number', 'float', 'double' => (string) $value,
            default => $value,
        };
    }

    /**
     * Seed default settings.
     */
    public function seedDefaults(): void
    {
        // Library Information
        $this->set('library.name', 'Perpustakaan Kampus', 'string', 'library_info', null, 'Nama perpustakaan');
        $this->set('library.address', '', 'string', 'library_info', null, 'Alamat perpustakaan');
        $this->set('library.phone', '', 'string', 'library_info', null, 'Nomor telepon');
        $this->set('library.email', '', 'string', 'library_info', null, 'Email perpustakaan');
        $this->set('library.open_hours', 'Senin - Jumat: 08:00 - 16:00', 'string', 'library_info', null, 'Jam operasional');

        // Loan Settings
        $this->set('loan.default_period', 14, 'integer', 'loan', null, 'Masa peminjaman default (hari)');
        $this->set('loan.max_renewal', 2, 'integer', 'loan', null, 'Maksimal perpanjangan');
        $this->set('loan.grace_period', 0, 'integer', 'loan', null, 'Grace period (hari)');
        $this->set('loan.auto_calc_fine', true, 'boolean', 'loan', null, 'Otomatis hitung denda');

        // Fine Settings
        $this->set('fine.daily_rate', 1000, 'integer', 'fine', null, 'Tarif denda per hari (rupiah)');
        $this->set('fine.max_fine', 50000, 'integer', 'fine', null, 'Maksimal denda per item (rupiah)');
        $this->set('fine.currency', 'IDR', 'string', 'fine', null, 'Mata uang');
        $this->set('fine.exclude_holidays', true, 'boolean', 'fine', null, 'Hitung denda di luar hari libur');

        // Reservation Settings
        $this->set('reservation.max_per_member', 5, 'integer', 'reservation', null, 'Maksimal reservasi per anggota');
        $this->set('reservation expiry_days', 2, 'integer', 'reservation', null, 'Masa berlaku reservasi (hari)');
        $this->set('reservation.allow_queue', true, 'boolean', 'reservation', null, 'Izinkan antrian reservasi');

        // Email Settings
        $this->set('email.from_address', 'noreply@library.com', 'string', 'email', null, 'Email pengirim');
        $this->set('email.from_name', 'Perpustakaan Kampus', 'string', 'email', null, 'Nama pengirim');
        $this->set('email.notifications_enabled', true, 'boolean', 'email', null, 'Aktifkan notifikasi email');

        // OPAC Settings
        $this->set('opac.items_per_page', 12, 'integer', 'opac', null, 'Item per halaman di OPAC');
        $this->set('opac.enable_search', true, 'boolean', 'opac', null, 'Aktifkan pencarian');
        $this->set('opac.show_cover', true, 'boolean', 'opac', null, 'Tampilkan cover gambar');
    }
}
