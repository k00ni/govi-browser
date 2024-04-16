FROM php:8.2-apache

# install and setup required system library
RUN apt-get update && apt-get install -y --no-install-recommends \
        curl git libicu-dev libpng-dev libzip-dev nano sudo unzip
RUN docker-php-ext-install -j$(nproc) intl zip

# install Composer globally (required to install PHP vendors)
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# add a non-root user to limit user rights
RUN useradd -r --home /home/govibrowser -u 1000 govibrowser
RUN usermod -a -G www-data govibrowser
RUN mkdir /home/govibrowser
RUN chown govibrowser:www-data /home/govibrowser
RUN adduser govibrowser sudo

COPY ./default.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY ./run.sh /run.sh
RUN chmod +x /run.sh

EXPOSE 80

CMD ["/run.sh"]