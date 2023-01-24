#!/bin/bash
echo 'Run PHP CodeSniffer';
./vendor/bin/phpcs
echo 'Run Laravel Pint';
./vendor/bin/pint
echo 'Run artisan test'
php artisan test
