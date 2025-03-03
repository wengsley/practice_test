Test Procedure

1. Composer install
2. Create database credentials and replace in .env
3. php artisan migrate
5. php artisan db:seed --class=UserTableSeeder.php
4. php artisan queue:work 
6. php artisan event:cache
7. php artisan serve
8. for Practice.postman_collection is the API document for testing
