# Quick Start Guide - Software Wiki

Get the wiki up and running in minutes for local development.

## Prerequisites

- PHP 8.2+
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url> wikisoftware
cd wikisoftware
```

### 2. Automated Installation (Recommended)

```bash
chmod +x install.sh
./install.sh
```

The script will:
- Install dependencies
- Create .env file
- Generate application key
- Run migrations and seeders
- Create storage symlink
- Set permissions
- Cache configuration

### 3. Manual Installation

If you prefer manual installation:

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database
mysql -u root -p
CREATE DATABASE wikisoftware CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Update .env with your database credentials
# DB_DATABASE=wikisoftware
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate --seed

# Create storage symlink
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 4. Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Credentials

After seeding, login with:

| Role   | Email                  | Password   |
|--------|------------------------|------------|
| Admin  | admin@wiki.local       | password   |
| Editor | editor@wiki.local      | password   |
| Viewer | viewer@wiki.local      | password   |

**⚠️ Change these passwords immediately in production!**

## Features Overview

### For All Users
- Browse published documents
- Search documents
- View categories
- Add documents to favorites
- Track reading history

### For Editors & Admins
- Create and edit documents
- Upload file attachments
- Add tags to documents
- Version control with history
- Publish or archive documents

### For Admins Only
- User management
- Category management
- View all documents (including drafts)
- Delete any document
- Access analytics

## Development Workflow

### Creating a Document

1. Click "New Document"
2. Fill in title, category, and content
3. Add tags (optional)
4. Upload attachments (optional)
5. Choose status (Draft or Published)
6. Click "Create Document"

### Editing a Document

1. Open any document
2. Click "Edit"
3. Make changes
4. Add change summary (optional)
5. Update status if needed
6. Click "Update Document"

A new version is automatically created!

### Managing Categories

Categories are pre-seeded:
- Training Manuals
- Onboarding Documentation
- De-Briefs
- Guides
- Proposals
- Checklists
- Briefs
- Planners
- Audits
- Other

Admins can add more via the admin panel.

### Search & Filter

- Use the search bar in navigation
- Filter by category on documents page
- Filter by tags
- Results show relevant excerpts

## File Uploads

Supported file types:
- Documents: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT
- Images: JPG, JPEG, PNG, GIF, SVG
- Archives: ZIP

Max file size: 10MB (configurable in .env)

## Customization

### Change Upload Limits

Edit `.env`:
```env
WIKI_MAX_UPLOAD_SIZE=20480  # 20MB
```

### Add Allowed File Types

Edit `.env`:
```env
WIKI_ALLOWED_FILE_TYPES=pdf,doc,docx,mp4,avi
```

### Adjust Pagination

Edit `.env`:
```env
WIKI_ITEMS_PER_PAGE=20
```

### Modify Categories

Edit `config/wiki.php` and re-run:
```bash
php artisan db:seed --class=CategorySeeder
```

## Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run tests
php artisan test

# Create new user
php artisan tinker
>>> User::create([
    'name' => 'New User',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'editor'
]);

# Backup database
mysqldump -u root -p wikisoftware > backup.sql

# Restore database
mysql -u root -p wikisoftware < backup.sql
```

## Troubleshooting

### Storage Link Not Working

```bash
# Remove existing link
rm public/storage

# Recreate
php artisan storage:link
```

### Permission Errors

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Error

1. Check MySQL is running: `mysql -u root -p`
2. Verify credentials in `.env`
3. Ensure database exists: `SHOW DATABASES;`

### 500 Error

1. Check `storage/logs/laravel.log`
2. Verify `.env` configuration
3. Clear cache: `php artisan optimize:clear`

## Next Steps

1. **Customize** colors and branding in views
2. **Add** more document templates
3. **Configure** email notifications
4. **Deploy** to production (see DEPLOYMENT.md)
5. **Backup** regularly

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [TinyMCE Documentation](https://www.tiny.cloud/docs/)

## Support

For issues or questions:
1. Check `storage/logs/laravel.log`
2. Review this guide
3. Consult DEPLOYMENT.md for production issues

---

**Happy documenting! 📚**
