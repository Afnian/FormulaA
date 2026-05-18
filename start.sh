#!/bin/bash

cat > /app/.env << EOF
APP_NAME="Fórmula A"
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

GEMINI_API_KEY=${GEMINI_API_KEY}
EOF

php artisan key:generate --force
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}