#!/bin/sh

# Exit on any error
set -e

echo "Starting Laravel application setup..."

# Wait for database to be ready (if using external database)
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for database connection..."
    until nc -z $DB_HOST $DB_PORT; do
        echo "Waiting for database at $DB_HOST:$DB_PORT..."
        sleep 2
    done
    echo "Database is ready!"
fi

# Generate application key if it doesn't exist
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Ensure Livewire and Filament assets are published
echo "Publishing Livewire and Filament assets..."
php artisan vendor:publish --tag=livewire:assets --force
php artisan filament:assets

echo "Application setup completed!"

# Execute the main command
exec "$@"