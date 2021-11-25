FROM php:8.0-apache
RUN apt update
RUN apt install wget unzip
RUN wget -O composer-setup.php https://getcomposer.org/installer
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN apt install nodejs -y
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www
WORKDIR /var/www

ENTRYPOINT ["php", "-S", "0.0.0.0:8080", "-t", "public"]