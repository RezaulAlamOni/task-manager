#!/bin/sh
cd /var/www/html/spark

php artisan down
php artisan migrate --force
php artisan cache:clear
php artisan up

touch ./storage/logs/laravel.log

cp /root/configs/compltit.net/spark.env /var/www/html/spark/.env

chmod -R ug+rwx ./storage ./bootstrap/cache

cd vendor/laravel
ln -s ../../spark/ spark-aurelius
