FROM php:7.3-apache 

RUN a2enmod rewrite

RUN service apache2 restart

ADD . /var/www/html


RUN docker-php-ext-install pdo_mysql


