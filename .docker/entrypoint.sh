#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run the database migrations
php artisan migrate --force

# Start the Apache server
exec apache2-foreground
