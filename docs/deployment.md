# Deployment Guide

This document provides instructions for deploying the School ERP + LMS platform to a production environment.

## Prerequisites

- PHP >= 8.2 with extensions: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_sqlite` (or `pdo_mysql`/`pdo_pgsql`), `tokenizer`, `xml`
- Composer >= 2.x
- Node.js >= 18.x and NPM >= 9.x (for asset compilation)
- Web server: Nginx (recommended) or Apache
- Database: SQLite, MySQL 8.0+, or PostgreSQL 15+
- (Optional) Redis for cache/session/queue
- (Optional) Supervisor for queue workers

## Step 1: Server Setup

### Install PHP and Extensions

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-mbstring php8.2-xml \
    php8.2-curl php8.2-zip php8.2-bcmath php8.2-sqlite3 php8.2-mysql \
    php8.2-pgsql php8.2-gd php8.2-intl
```

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

## Step 2: Application Deployment

### Clone and Install

```bash
cd /var/www
git clone https://github.com/mohammedcherifmohamed/erp_v2.git
cd erp_v2

composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for production:

```env
APP_NAME="School ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_v2
DB_USERNAME=erp_user
DB_PASSWORD=secure_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Database Migration

```bash
php artisan migrate --force
```

### Permissions

```bash
sudo chown -R www-data:www-data /var/www/erp_v2
sudo chmod -R 755 /var/www/erp_v2
sudo chmod -R 775 /var/www/erp_v2/storage
sudo chmod -R 775 /var/www/erp_v2/bootstrap/cache
```

### Storage Link

```bash
php artisan storage:link
```

## Step 3: Web Server Configuration

### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/erp_v2/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache

Ensure `mod_rewrite` is enabled. The `.htaccess` file in the `public/` directory handles URL rewriting.

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/erp_v2/public

    <Directory /var/www/erp_v2/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/erp-error.log
    CustomLog ${APACHE_LOG_DIR}/erp-access.log combined
</VirtualHost>
```

## Step 4: SSL/HTTPS (Recommended)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## Step 5: Queue Worker

For processing queued notifications and jobs:

### Using Supervisor

```bash
sudo apt install supervisor
```

Create `/etc/supervisor/conf.d/erp-queue-worker.conf`:

```ini
[program:erp-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/erp_v2/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/erp-queue-worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start erp-queue-worker:*
```

## Step 6: Scheduled Tasks

Add the Laravel scheduler to the system crontab:

```bash
sudo crontab -e -u www-data
```

Add this line:

```
* * * * * cd /var/www/erp_v2 && php artisan schedule:run >> /dev/null 2>&1
```

## Step 7: Optimization

Run these commands for production performance:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

## Step 8: Monitoring

### Log Files

Application logs are stored in `storage/logs/laravel.log`. Configure log rotation:

```bash
# Add to /etc/logrotate.d/erp
/var/www/erp_v2/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
}
```

### Health Check

You can verify the application is running by accessing the root URL. The landing page should load without errors.

## Updating the Application

```bash
cd /var/www/erp_v2

# Pull latest changes
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan optimize

# Restart queue workers
sudo supervisorctl restart erp-queue-worker:*
```

## Troubleshooting

| Issue                         | Solution                                               |
|-------------------------------|--------------------------------------------------------|
| 500 Internal Server Error     | Check `storage/logs/laravel.log` for details          |
| Permission denied errors      | Run `chmod -R 775 storage bootstrap/cache`            |
| Database connection refused   | Verify DB credentials in `.env`                       |
| Assets not loading            | Run `npm run build` and `php artisan storage:link`    |
| Emails not sending            | Check MAIL_* settings and run `php artisan queue:work`|
| Slow performance              | Run `php artisan optimize` and enable OPcache         |
