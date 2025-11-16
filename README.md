# Software Wiki - Internal Documentation System

A comprehensive internal wiki system built with Laravel 11 for managing departmental documentation, guides, training materials, and more.

## Features

- **Document Management**: Full CRUD operations for documents with rich text editing
- **10 Predefined Categories**: Training Manuals, Onboarding, De-Briefs, Guides, Proposals, Checklists, Briefs, Planners, Audits, and Other
- **Document Versioning**: Track changes and maintain document history
- **File Attachments**: Upload and manage multiple files per document
- **Advanced Search**: Full-text search with filters by category, tags, and date
- **User Roles**: Admin, Editor, and Viewer roles with appropriate permissions
- **Tagging System**: Flexible tag-based organization
- **Favorites**: Bookmark important documents
- **Recent History**: Track recently viewed documents
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile
- **Shared Hosting Compatible**: Designed to work on standard cPanel hosting

## Tech Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL 5.7+
- **Frontend**: Blade Templates
- **CSS**: TailwindCSS 3.x (via CDN)
- **JavaScript**: Alpine.js 3.x (via CDN)
- **WYSIWYG Editor**: TinyMCE (via CDN)
- **Icons**: Heroicons

## Requirements

- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Apache with mod_rewrite enabled
- PHP Extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

## Installation (Local Development)

### 1. Clone the Repository

```bash
git clone <repository-url> wikisoftware
cd wikisoftware
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wikisoftware
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Database

Create a MySQL database named `wikisoftware` (or your chosen name):

```sql
CREATE DATABASE wikisoftware CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create all necessary tables and populate:
- 10 predefined categories
- Sample admin user
- Example documents (optional)

### 7. Create Storage Symlink

```bash
php artisan storage:link
```

### 8. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Default Login Credentials

After seeding, you can login with:
- **Email**: admin@wiki.local
- **Password**: password

**⚠️ IMPORTANT**: Change these credentials immediately after first login!

## Installation (Shared Hosting - cPanel)

### 1. Prepare Files

1. Download or clone the repository to your local machine
2. Run `composer install --optimize-autoloader --no-dev`
3. Create and configure `.env` file with production settings

### 2. Upload Files

Upload all files to your hosting account:
- Option A: Upload to `public_html/wiki/` (subdirectory installation)
- Option B: Upload to `public_html/` (root installation)

### 3. Database Setup via cPanel

1. Go to cPanel → MySQL Databases
2. Create a new database
3. Create a new MySQL user
4. Add user to database with ALL PRIVILEGES
5. Note the database name, username, and password

### 4. Configure .env

Update your `.env` file with production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_cpanel_database
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_database_password
```

### 5. Run Migrations via SSH or Terminal

If SSH access is available:

```bash
cd /home/username/public_html/wiki
php artisan migrate --seed
php artisan storage:link
```

If SSH is not available, use cPanel Terminal or contact your hosting provider.

### 6. Set Folder Permissions

Set the following permissions via cPanel File Manager:

- `storage/` → 775 (recursive)
- `bootstrap/cache/` → 775 (recursive)

### 7. Configure Document Root

**Option A - Subdirectory Installation**:
The `.htaccess` in the root will redirect to `/public` automatically.

**Option B - Root Installation**:
If possible, point your domain's document root to the `/public` directory via cPanel.

### 8. Clear Cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Configuration

### Upload Limits

Configure in `.env`:

```env
WIKI_MAX_UPLOAD_SIZE=10240
# Maximum file size in KB (10MB default)

WIKI_ALLOWED_FILE_TYPES=pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,svg,zip
# Allowed file extensions
```

### Pagination

```env
WIKI_ITEMS_PER_PAGE=15
# Number of documents per page
```

## Usage

### User Roles

1. **Administrator**
   - Full access to all features
   - User management
   - Category management
   - Can publish/archive any document

2. **Editor**
   - Create and edit documents
   - Upload attachments
   - Manage own documents
   - View all published documents

3. **Viewer**
   - Read-only access
   - View published documents
   - Download attachments
   - Add to favorites

### Document States

- **Draft**: Work in progress, visible only to author and admins
- **Published**: Visible to all users
- **Archived**: Hidden from normal view, accessible via archive section

## Maintenance

### Backup Database

```bash
php artisan backup:run
# or via mysqldump
mysqldump -u username -p wikisoftware > backup.sql
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Update Dependencies

```bash
composer update
php artisan migrate
```

## Security

- CSRF protection on all forms
- XSS protection via HTML purification
- SQL injection prevention via Eloquent ORM
- Role-based access control
- Secure file uploads with validation
- Rate limiting on search and login

## Troubleshooting

### 500 Internal Server Error

1. Check `.env` configuration
2. Verify database credentials
3. Ensure `storage/` and `bootstrap/cache/` are writable
4. Check Apache error logs

### Storage Link Not Working

Manually create symlink:

```bash
ln -s ../storage/app/public public/storage
```

Or via PHP:

```php
symlink('../storage/app/public', 'public/storage');
```

### File Upload Issues

1. Check `php.ini` settings:
   - `upload_max_filesize`
   - `post_max_size`
   - `max_execution_time`

2. Verify storage permissions:
   ```bash
   chmod -R 775 storage/
   ```

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

## Support

For issues, questions, or contributions, please contact the development team.

## License

This software is proprietary and confidential. Unauthorized copying or distribution is prohibited.

---

**Built with ❤️ for the Software Department**
