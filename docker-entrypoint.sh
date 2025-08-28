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

# Display current APP_URL for debugging
echo "APP_URL is set to: $APP_URL"

# Set session secure flag for HTTPS
if echo "$APP_URL" | grep -q "https://"; then
    export SESSION_SECURE_COOKIE=true
    echo "Setting SESSION_SECURE_COOKIE=true for HTTPS"
else
    export SESSION_SECURE_COOKIE=false
    echo "Setting SESSION_SECURE_COOKIE=false for HTTP"
fi

# Warn if APP_URL looks incorrect for production
if [ "$APP_ENV" = "production" ] && echo "$APP_URL" | grep -q "localhost\|ddev"; then
    echo "WARNING: APP_URL contains localhost or ddev in production environment!"
    echo "This will cause Livewire file upload signature validation to fail."
    echo "Please set APP_URL to your production domain in Coolify environment variables."
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

# Create Livewire temporary upload directory
echo "Creating Livewire temporary upload directory..."
mkdir -p /var/www/html/storage/app/livewire-tmp
chown -R www-data:www-data /var/www/html/storage/app/livewire-tmp
chmod -R 755 /var/www/html/storage/app/livewire-tmp

# Ensure Livewire and Filament assets are published
echo "Publishing Livewire and Filament assets..."
php artisan vendor:publish --tag=livewire:assets --force
php artisan filament:assets

echo "Application setup completed!"

# Execute the main command
exec "$@"