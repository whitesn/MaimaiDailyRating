# Maimai Live Rating

Fetcher untuk membuat ladder gabungan dari beberapa arcade dari maimai-net.

## Dependencies
1. PHP (Make sure PHP is in System Environment Path)
2. Webserver (Apache, Nginx, etc.)

## How to Use?
1. Modify the list of arcades to be shown in /database/seeds/ArcadeTableSeeder.php
2. Copy .env.example to .env and modify the Database credentials.
2. php artisan migrate
3. php artisan db:seed 
4. Input Grabber accounts through phpmyadmin / MySQL CLI / Laravel Seeder
5. Add cron job to execute 'php artisan -vv schedule:run >> grabber.txt' to have it grabbing and update data periodically :)

## Demo (Indonesia Live Rating)
http://ranking.mmcb.co/