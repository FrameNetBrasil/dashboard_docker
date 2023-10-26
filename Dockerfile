FROM framenetbrasil/apache-php:v3.8

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get --allow-releaseinfo-change update
RUN apt install -y graphviz

COPY ./ /var/www/html

WORKDIR /var/www/html

RUN composer install

CMD ["apache2-foreground"]
