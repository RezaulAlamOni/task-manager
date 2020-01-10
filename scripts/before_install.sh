#!/bin/sh
cd /var/www/html/
rm -Rf spark-last
mkdir spark-last
mv spark spark-last
mkdir spark
systemctl stop compltitNotification.service
