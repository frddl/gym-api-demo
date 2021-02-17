rm -f composer.lock

php artisan down

composer dump-autoload --no-interaction
composer install --no-interaction
composer update --no-interaction

php artisan config:clear
php artisan cache:clear

php artisan migrate
php artisan up
