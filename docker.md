<!-- composer require nuwave/lighthouse -->
composer install
php artisan vendor:publish --tag=lighthouse-schema
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret