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

echo "Starting application services..."
exec "$@"
