#!/bin/bash
# Correr SIEMPRE después de "Deploy HEAD" en cPanel, sin excepción.
#
# Por qué existe: el deploy automático de cPanel (.cpanel.yml) viene fallando
# de forma silenciosa — a veces no corre la migración, a veces no refresca
# las rutas, y cPanel igual dice "deployment complete" sin avisar. Este
# script deja todo en el estado correcto pase lo que pase con el deploy
# automático. Es seguro correrlo aunque el deploy automático SÍ haya
# funcionado bien — ninguno de estos pasos rompe nada por repetirse.
#
# Uso:
#   cd /home/ochotierras/api.ochotierras.cl
#   bash deploy-post.sh

set -e  # si algo falla, para acá y avisa, no sigue como si nada.

PHP=/opt/cpanel/ea-php82/root/usr/bin/php

echo "== 1/6: composer =="
# Este hosting NO tiene el comando "composer" instalado en el sistema
# (se confirmó por SSH: ni /usr/local/bin/composer ni /opt/cpanel/composer/
# tienen nada útil). El vendor/ que hoy corre en producción se instaló una
# sola vez a mano desde "Setup PHP App" en cPanel, nunca desde acá. Para no
# depender de eso, el script se trae su propia copia de Composer la primera
# vez y la reutiliza en cada deploy siguiente (no se vuelve a descargar).
if [ ! -f composer.phar ]; then
    echo "   (primera vez: descargando composer.phar — los próximos deploys ya no necesitan esto)"
    $PHP -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    $PHP composer-setup.php --quiet
    rm -f composer-setup.php
fi
$PHP composer.phar install --no-dev --optimize-autoloader

echo "== 2/6: migraciones =="
$PHP artisan migrate --force

echo "== 3/6: config cache =="
$PHP artisan config:clear
$PHP artisan config:cache

echo "== 4/6: route cache =="
$PHP artisan route:clear
$PHP artisan route:cache

echo "== 5/6: view cache =="
$PHP artisan view:clear
$PHP artisan view:cache

echo "== 6/6: storage:link =="
$PHP artisan storage:link || true  # ya existe la mayoría de las veces, no es un error

echo ""
echo "== Verificación =="
$PHP artisan migrate:status | tail -5

echo ""
echo "Listo. Si alguna migración dice 'Pending' arriba, algo salió mal — avisale a Claude."
