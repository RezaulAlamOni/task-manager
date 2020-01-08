#!/bin/sh
cd /var/www/html/spark

cp /root/configs/compltit.net/spark.env /var/www/html/spark/.env
touch ./storage/logs/laravel.log

php artisan down
php artisan migrate --force
php artisan cache:clear
php artisan up

cd /var/www/html/spark/public/
ln -s /var/www/html/storage/uploads/ storage
cd ..
chmod -R a+rwx ./storage ./bootstrap/cache

cd vendor/laravel
ln -s ../../spark/ spark-aurelius

systemctl start compltitNotification.service
