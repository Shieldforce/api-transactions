#!/bin/bash
./vendor/bin/sail artisan optimize
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
