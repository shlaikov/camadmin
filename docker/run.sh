#!/usr/bin/env bash

cd /var/www

### If first initial
if [[ -z "${APP_KEY}" ]]; then
    export APP_KEY=$(php artisan key:generate --show)

    php artisan storage:link
fi

php artisan optimize
php artisan migrate --force

/usr/bin/supervisord -c /etc/supervisord.conf
