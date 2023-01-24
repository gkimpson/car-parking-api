#!/bin/bash
echo 'Run Laravel Pint';
./vendor/bin/pint
echo 'Run artisan test'
php artisan test
echo 'Run PHPStan'
./vendor/bin/phpstan
