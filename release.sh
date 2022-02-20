#!/usr/bin/env bash

php artisan migrate --force
php artisan l5-swagger:generate
