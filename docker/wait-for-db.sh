#!/bin/sh

# wait-for-db.sh - Wait for database to be ready before starting services

set -e

# Get database connection details from environment variables
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-3306}

echo "Waiting for database at $DB_HOST:$DB_PORT..."

# Wait for database to be ready
until nc -z "$DB_HOST" "$DB_PORT"; do
    echo "Database is unavailable - sleeping"
    sleep 2
done

echo "Database is up - preparing Laravel application"

# Run Laravel optimization commands
echo "Running Laravel optimization..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

# Try to run migrations (if database is ready)
echo "Running database migrations..."
if php artisan migrate --force --no-interaction; then
    echo "Migrations completed successfully"
else
    echo "Migrations failed, but continuing startup..."
fi

echo "Starting application services..."
exec "$@"
