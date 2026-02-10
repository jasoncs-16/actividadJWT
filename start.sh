#!/bin/bash

# Generar caché de configuración
php artisan config:cache
php artisan route:cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Iniciar Nginx en primer plano
nginx -g "daemon off;"