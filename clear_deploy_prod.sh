#!/bin/bash

composer update
php artisan optimize
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

