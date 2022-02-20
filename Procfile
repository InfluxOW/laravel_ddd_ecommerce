web: vendor/bin/heroku-php-apache2 public/
release: ./release.sh

queues: php artisan queue:work --name=queues --queue=default,resizer,notifications --timeout=1800 --tries=5
default: php artisan queue:work --name=default --queue=default --timeout=1800 --tries=5
resizer: php artisan queue:work --name=resizer --queue=resizer --timeout=1800 --tries=5
notifications: php artisan queue:work --name=notifications --queue=notifications --timeout=1800 --tries=5

schedule: php artisan schedule:work
