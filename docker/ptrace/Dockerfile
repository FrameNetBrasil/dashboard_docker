FROM php:8.1.11-zts-alpine3.16
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#CMD [ "php", "/home/src/server-ws.php &" ]
#CMD [ "php", "/home/src/server-trace.php" ]
#CMD start.sh
COPY . /home
RUN composer install -d /home
CMD [ "php", "/home/src/p-trace.php" ]
