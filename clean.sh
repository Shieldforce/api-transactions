#!/bin/bash
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail artisan migrate
