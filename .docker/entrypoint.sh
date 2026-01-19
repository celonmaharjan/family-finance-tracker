#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run the database migrations
echo "Running database migrations..."
php artisan migrate --force
echo "Migrations completed."

# Check if the database needs seeding (e.g., no users exist yet)
# We use 'php artisan tinker' to get a count of users
if [ "$(php artisan tinker --execute="echo \App\Models\User::count();")" -eq 0 ]; then
    echo "Database is empty, running seeders..."
    php artisan db:seed
    echo "Seeding completed."
else
    echo "Database already contains data, skipping seeding."
fi

# Start the Apache server
exec apache2-foreground