git pull

composer install
composer dump-autoload

php artisan migrate --force

#TODO: Remove this late
php artisan db:seed --force
php artisan optimize

php artisan queue:restart

npm install
npm run build

