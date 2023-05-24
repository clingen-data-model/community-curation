#!/bin/bash

php artisan clear-compiled
php artisan migrate --force --no-interaction
php artisan db:seed --class=RolesAndPermissionTablesSeeder --force --no-interaction
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan storage:link

# FIXME- the notification uses a hard-coded user id that is not currently active
# php artisan notify:deployed
