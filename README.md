# SPA Comments Project

Це приклад SPA (Single Page Application) з функціоналом коментарів на основі Laravel та Docker.

## Вимоги

- **Docker** і **Docker Compose** встановлені локально.
- Опціонально: **Git**, щоб клонувати репозиторій.

## Кроки встановлення та запуску

1. **Клонувати** репозиторій з GitHub (якщо ще не клонували):
   ```bash
   git clone https://github.com/DmytroKviten/SPA-comment.git

2. Створити .env файл і вставити в нього: 

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:lNn76hZmQLjphm6muLyydvx3f+xGrSz2tgNk0eZpr0E=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"



3. Запустити Docker:

docker-compose build --no-cache
docker-compose up -d

4. Встановити залежності через Composer 

docker-compose exec app composer install
composer install

5. Згенерувати Laravel-ключ

docker-compose exec app php artisan key:generate
php artisan key:generate

6. Запустити міграції для бд

docker-compose exec app php artisan migrate
php artisan migrate

7. Перевірка 

http://localhost:8000

