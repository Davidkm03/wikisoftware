#!/bin/bash

# Software Wiki - Installation Script
# This script helps automate the installation process

echo "========================================="
echo "  Software Wiki - Installation Script  "
echo "========================================="
echo ""

# Check if running with proper permissions
if [ ! -w "." ]; then
    echo "Error: No write permission in current directory"
    exit 1
fi

echo "Step 1: Checking PHP version..."
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "PHP Version: $PHP_VERSION"

if [ "$(printf '%s\n' "8.2" "$PHP_VERSION" | sort -V | head -n1)" != "8.2" ]; then
    echo "Error: PHP 8.2 or higher is required"
    exit 1
fi
echo "✓ PHP version OK"
echo ""

echo "Step 2: Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    echo "✓ Dependencies installed"
else
    echo "⚠ Composer not found. Please install dependencies manually:"
    echo "  composer install --optimize-autoloader --no-dev"
fi
echo ""

echo "Step 3: Environment configuration..."
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
    echo "✓ .env file created"
    echo "⚠ Please edit .env file with your database credentials"
else
    echo "⚠ .env file already exists, skipping..."
fi
echo ""

echo "Step 4: Generating application key..."
php artisan key:generate
echo "✓ Application key generated"
echo ""

echo "Step 5: Setting up database..."
read -p "Do you want to run migrations now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Running migrations..."
    php artisan migrate --seed
    echo "✓ Database migrated and seeded"
else
    echo "⚠ Skipping migrations. Run manually: php artisan migrate --seed"
fi
echo ""

echo "Step 6: Creating storage symlink..."
php artisan storage:link
echo "✓ Storage link created"
echo ""

echo "Step 7: Setting file permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✓ Permissions set"
echo ""

echo "Step 8: Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Cache created"
echo ""

echo "========================================="
echo "  Installation Complete!  "
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Configure your web server to point to the 'public' directory"
echo "2. Update your .env file with production settings"
echo "3. Login with default credentials:"
echo "   Email: admin@wiki.local"
echo "   Password: password"
echo "4. CHANGE THE DEFAULT PASSWORD IMMEDIATELY!"
echo ""
echo "For deployment to cPanel, see DEPLOYMENT.md"
echo ""
