# Panduan Instalasi & Deployment
## Aplikasi Perpustakaan Kampus

---

## Persyaratan Sistem (System Requirements)

### Server Requirements
- **PHP**: 8.2 atau higher
- **Database**: MySQL 8.0+ / MariaDB 10.6+ / PostgreSQL 12+ / SQLite 3.8.8+
- **Web Server**: Apache 2.4+ / Nginx 1.18+ dengan mod_rewrite
- **Redis**: 5.0+ (recommended untuk caching & queues)
- **Composer**: 2.x
- **Node.js**: 18+ / 20+ (untuk build assets)
- **PHP Extensions**:
  - BCMath, Ctype, cURL, DOM, Fileinfo, JSON, MBstring, OpenSSL, PCRE
  - PDO, Tokenizer, XML, GD, Imagick atau ImageMagick

### Recommended Server Specs
- **Minimal**: 2 CPU Cores, 4GB RAM, 20GB Storage
- **Recommended**: 4 CPU Cores, 8GB RAM, 50GB SSD
- **Untuk 500+ User**: 8 CPU Cores, 16GB RAM, 100GB SSD

---

## Instalasi Baru

### 1. Clone Repository

```bash
git clone https://github.com/your-org/aplikasi-perpustakaan.git
cd aplikasi-perpustakaan/laravel
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install frontend dependencies
npm ci
npm run build
```

### 3. Environment Configuration

```bash
# Copy environment template
cp .env.production.example .env

# Generate application key
php artisan key:generate
```

### 4. Edit File .env

```bash
nano .env
```

**PENTING: Update nilai berikut:**
- `APP_NAME` - Nama aplikasi
- `APP_URL` - URL aplikasi (misal: https://library.univ.ac.id)
- `DB_*` - Konfigurasi database
- `REDIS_*` - Konfigurasi Redis
- `MAIL_*` - Konfigurasi email
- `INITIAL_ADMIN_*` - Email dan password admin awal

### 5. Setup Database

```bash
# Migrate database
php artisan migrate --force

# Seed database (role, permissions, settings)
php artisan db:seed --force --class=PermissionSeeder
php artisan db:seed --force --class=SettingsSeeder
```

### 6. Setup Storage Links

```bash
php artisan storage:link
```

### 7. Setup Queue Worker

```bash
# Install supervisor untuk queue worker
sudo apt install supervisor

# Buat supervisor config
sudo nano /etc/supervisor/conf.d/perpustakaan-worker.conf
```

**Isi file:**
```ini
[program:perpustakaan-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aplikasi-perpustakaan/laravel/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/aplikasi-perpustakaan/laravel/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Update supervisor dan start worker
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start perpustakaan-worker:*
```

### 8. Setup Scheduler (Cron Jobs)

```bash
# Edit crontab
crontab -e
```

**Tambahkan baris berikut:**
```cron
* * * * * cd /var/www/aplikasi-perpustakaan/laravel && php artisan schedule:run >> /dev/null 2>&1
```

---

## Deployment ke Production

### 1. Optimasi Composer

```bash
# Optimize composer autoloader
composer install --optimize-autoloader --no-dev
```

### 2. Optimasi Cache

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all cache
php artisan cache:clear
```

### 3. Set Permissions

```bash
# Storage dan cache directories harus writable
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Web Server Configuration

#### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name library.example.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name library.example.com;
    root /var/www/aplikasi-perpustakaan/laravel/public;

    # SSL Configuration
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Configuration (.htaccess sudah include)

Jika menggunakan Apache, pastikan `mod_rewrite` dan `mod_ssl` aktif:

```bash
sudo a2enmod rewrite ssl
sudo systemctl restart apache2
```

---

## Maintenance

### Backup Database

```bash
# Backup manual
php artisan backup:run --only-db

# Backup otomatis via scheduler (setup di .env)
BACKUP_ENABLED=true
BACKUP_SCHEDULE="0 2 * * *"  # Setiap jam 2 malam
```

### Update Aplikasi

```bash
# Pull latest code
git pull origin main

# Install new dependencies
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
sudo supervisorctl restart perpustakaan-worker:*
```

### Monitoring

Cek log secara berkala:
```bash
tail -f storage/logs/laravel.log
```

Cek queue worker status:
```bash
sudo supervisorctl status perpustakaan-worker:*
```

---

## Troubleshooting

### 404 Error pada semua halaman
- Pastikan `mod_rewrite` aktif (Apache)
- Cek konfigurasi Nginx untuk `try_files`

### 500 Internal Server Error
- Cek `storage/logs/laravel.log`
- Pastikan permissions sudah benar
- Verify `.env` configuration

### Upload file gagal
- Cek `php.ini` untuk `upload_max_filesize` dan `post_max_size`
- Pastikan directory `storage/app/public` writable

### Queue tidak diproses
- Restart queue worker: `sudo supervisorctl restart perpustakaan-worker:*`
- Cek log: `tail -f storage/logs/worker.log`

---

## Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `APP_KEY` di-generate dengan random string
- [ ] HTTPS aktif dengan valid SSL certificate
- [ ] Firewall untuk membatasi akses database & Redis
- [ ] Regular backup database dan files
- [ ] Strong password untuk admin dan database
- [ ] Rate limiting aktif untuk API
- [ ] File upload validation aktif
- [ ] Regular security update: `composer update`

---

## Support

Untuk pertanyaan dan issue, hubungi:
- Email: support@example.com
- Issues: https://github.com/your-org/aplikasi-perpustakaan/issues
