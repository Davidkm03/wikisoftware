# Deployment Guide - cPanel Shared Hosting

This guide will help you deploy the Software Wiki application to a shared hosting environment using cPanel.

## Prerequisites

- Access to cPanel
- PHP 8.2+ available on your hosting
- MySQL database access
- SSH access (optional but recommended)
- Composer installed (or ability to run locally)

## Deployment Steps

### Step 1: Prepare Your Local Files

1. **Install dependencies locally** (if not already done):
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

2. **Create production .env file**:
   ```bash
   cp .env.example .env.production
   ```

3. **Edit .env.production** with your production settings:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=your_cpanel_database
   DB_USERNAME=your_cpanel_user
   DB_PASSWORD=your_secure_password
   ```

4. **Generate application key** (save this for later):
   ```bash
   php artisan key:generate --show
   ```

### Step 2: Upload Files to cPanel

#### Option A: Via File Manager (Recommended for small projects)

1. **Compress your project**:
   ```bash
   zip -r wikisoftware.zip . -x "node_modules/*" "*.git/*" ".env"
   ```

2. **Upload via cPanel File Manager**:
   - Login to cPanel
   - Go to File Manager
   - Navigate to `public_html/` (or subdirectory)
   - Upload `wikisoftware.zip`
   - Extract the archive

#### Option B: Via FTP/SFTP

1. Use FileZilla or any FTP client
2. Upload all files to `public_html/wiki/` (or your chosen directory)
3. **Do NOT upload**: `.git/`, `node_modules/`, `.env`

### Step 3: Database Setup

1. **Create Database via cPanel**:
   - Go to MySQL Databases
   - Create new database: `username_wikisoft`
   - Create new user with strong password
   - Add user to database with ALL PRIVILEGES

2. **Note your credentials**:
   - Database name
   - Database user
   - Database password
   - Database host (usually `localhost`)

### Step 4: Configure Environment

1. **Create .env file on server**:
   - Via File Manager, create new file: `.env`
   - Copy contents from `.env.production`
   - Update with your database credentials
   - Add your APP_KEY generated earlier

2. **Verify .env configuration**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:YourGeneratedKeyHere
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=username_wikisoft
   DB_USERNAME=username_wikiuser
   DB_PASSWORD=your_secure_password
   ```

### Step 5: Set File Permissions

Via File Manager or SSH, set the following permissions:

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

For better security, you may want:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 6: Run Migrations

#### If SSH is available:

```bash
cd /home/username/public_html/wiki
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### If SSH is NOT available:

1. Create a temporary migration script `migrate.php` in public directory:

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Run migrations
Artisan::call('migrate', ['--seed' => true, '--force' => true]);
echo Artisan::output();

// Create storage link
Artisan::call('storage:link');
echo Artisan::output();

// Cache config
Artisan::call('config:cache');
echo Artisan::output();

echo "\nMigrations completed successfully!";
```

2. Visit `https://yourdomain.com/migrate.php` in your browser
3. **IMPORTANT**: Delete `migrate.php` after successful migration!

### Step 7: Configure Document Root (Optional)

If your hosting supports changing document root:

1. In cPanel, go to "Domains" or "Document Root"
2. Point your domain to `/public_html/wiki/public`
3. This is more secure as it hides Laravel files

If you cannot change document root, the root `.htaccess` will handle redirection.

### Step 8: Create Storage Symlink

The symlink connects public storage to the storage directory.

#### Via SSH:
```bash
php artisan storage:link
```

#### Without SSH:
1. Create a file `symlink.php` in public directory:

```php
<?php
$target = $_SERVER['DOCUMENT_ROOT'].'/../storage/app/public';
$link = $_SERVER['DOCUMENT_ROOT'].'/storage';

if (file_exists($link)) {
    echo "Symlink already exists!";
} else {
    symlink($target, $link);
    echo "Symlink created successfully!";
}
```

2. Visit `https://yourdomain.com/symlink.php`
3. Delete `symlink.php` after creation

### Step 9: Verify Installation

1. Visit your domain: `https://yourdomain.com`
2. You should see the login page
3. Login with default credentials:
   - Email: `admin@wiki.local`
   - Password: `password`
4. **IMMEDIATELY** change the admin password!

## Post-Deployment

### Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Verify `.env` is NOT publicly accessible
- [ ] Ensure `storage/` has correct permissions
- [ ] Delete any temporary migration scripts
- [ ] Review user roles and permissions

### Maintenance Tasks

#### Update Application:

```bash
# Backup database first!
php artisan down
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

#### Clear Cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Backup Database:

```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

## Common Issues

### Issue: 500 Internal Server Error

**Solutions:**
1. Check `.env` file exists and is configured correctly
2. Verify PHP version is 8.2+
3. Check `storage/` and `bootstrap/cache/` permissions
4. Review error logs in `storage/logs/laravel.log`
5. Enable error display temporarily:
   ```env
   APP_DEBUG=true
   ```

### Issue: Storage Link Not Working

**Solution:**
Manually create symlink as described in Step 8.

### Issue: File Upload Fails

**Solutions:**
1. Check PHP upload limits in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
2. Verify storage permissions: `chmod -R 775 storage`

### Issue: Database Connection Error

**Solutions:**
1. Verify database credentials in `.env`
2. Ensure database user has proper privileges
3. Check database host (might be different from `localhost`)
4. Verify MySQL is running

### Issue: CSRF Token Mismatch

**Solutions:**
1. Clear browser cookies
2. Run: `php artisan config:cache`
3. Check session configuration in `.env`

## Support

For additional help:
- Check Laravel logs: `storage/logs/laravel.log`
- Review server error logs in cPanel
- Contact your hosting provider for PHP/MySQL issues

## Important Notes

1. **Never commit `.env` to version control**
2. **Always backup before updating**
3. **Test in staging environment first**
4. **Keep Laravel and dependencies updated**
5. **Monitor storage space and database size**

---

**Deployment Complete!** Your Software Wiki should now be running on shared hosting.
