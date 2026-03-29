#!/bin/bash
# Clear all Laravel caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
echo "All caches cleared and rebuilt!"
