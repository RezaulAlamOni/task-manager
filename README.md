setup an appropriate ```/.env``` file.

run ```composer install``` to install php requirements.

after running composer install, you will need to:
1. create a folder called "spark-aurelius" in the vendor/laravel folder.
2. copy(not move) the entire contents of the spark directory in the root of the project to the folder created in step 1.  

run ```php artisan migrate --force``` to run all database migrations.

run ```npm install``` to install node packages.

if this is your first installation and you would like access to the kiosk, please add the email address you registered with to the ```$developers``` array defined in: ```/app/Providers/SparkServiceProvider.php``
