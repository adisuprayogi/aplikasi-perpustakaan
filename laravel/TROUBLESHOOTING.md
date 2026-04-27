# Quick Fix: Table Not Found Error

## Problem
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'db_perpus_dev.collections' doesn't exist
```

## Quick Solution (SSH ke Server)

### 1. Login ke Server
```bash
ssh user@dev-library.tazkia.ac.id
cd /path/to/laravel
```

### 2. Cek .env Configuration
```bash
cat .env | grep DB_
```

Pastikan koneksi database sudah benar:
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=db_perpus_dev`
- `DB_USERNAME=your_db_user`
- `DB_PASSWORD=your_db_password`

### 3. Test Koneksi Database
```bash
php artisan tinker
```

```php
>>> \DB::connection()->getDatabaseName();
=> "db_perpus_dev"

>>> \Schema::hasTable('collections');
=> false  // Artinya tabel belum ada
```

### 4. Jalankan Migration
```bash
# Keluar dari tinker (CTRL+D)

# Jalankan semua migration
php artisan migrate --force

# Output yang diharapkan:
# ✓ Migrating: 2026_01_27_034124_create_branches_table
# ✓ Migrating: 2026_01_27_034255_create_authors_table
# ✓ Migrating: 2026_01_27_034305_create_collections_table
# ✓ ... (dan seterusnya)
```

### 5. Cek Migration Status
```bash
php artisan migrate:status
```

Output harus menampilkan semua migration:
```
Migration name .................................................................... Batch / Status
2026_01_27_034124_create_branches_table ................................. [1] Ran
2026_01_27_034255_create_authors_table .................................. [1] Ran
2026_01_27_034305_create_collections_table ............................ [1] Ran
...
```

### 6. Seed Permissions (jika belum)
```bash
php artisan db:seed --class=PermissionSeeder --force
```

### 7. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 8. Verify Aplikasi
Buka browser dan akses:
- https://dev-library.tazkia.ac.id/opac

---

## Automated Deployment Script

Untuk deployment penuh, gunakan:
```bash
cd /path/to/laravel
./deploy.sh
```

---

## Jika Masih Error

### Cek Log Laravel
```bash
tail -100 storage/logs/laravel.log
```

### Cek Permission
```bash
ls -la database/migrations/
# Harus bisa dibaca oleh user web server
chmod 644 database/migrations/*.php
```

### Manual Migration Per Table
```bash
# Jika ada migration yang gagal, jalankan satu per satu
php artisan migrate --path=database/migrations/2026_01_27_034305_create_collections_table.php --force
```

---

## Prevention (Untuk Deployment Selanjutnya)

Gunakan script deployment otomatis:
```bash
# Dalam deploy.sh sudah termasuk:
php artisan migrate --force
php artisan db:seed --class=PermissionSeeder --force
php artisan storage:link
```

Atau gunakan deployment tools:
- Laravel Envoy
- GitHub Actions
- GitLab CI/CD
