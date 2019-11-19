# Sport-Field-Rental
This application was made in order to finish my thesis.
## How to Install
In order to run the application, you need to have composer installed and then run
```
composer install
```
You need to set the database name, application name in the .env by copying the .env.example.
After you create the .env, run 
```
php artisan key:generate
```
and
```
php artisan migrate:fresh --seed
```
Also since the application use the storage in the application, you'll need to run
```
php artisan storage:link
```
