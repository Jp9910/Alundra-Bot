FROM php:8.1-cli

# Copy source code and dependencies file to inside-the-container folder /app/
COPY ./src/ ./composer.json ./.env ./resources/images/emote.png /app/

# Copy xdebug config file
COPY ./99-xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install linux packages
RUN apt-get update && apt-get install -y vim git zip unzip

# Set workdir
WORKDIR /app

# Install php extensions
#RUN docker-php-ext-install

# Install composer extensions
RUN composer install

#RUN apt-get update && apt-get install -y vim iputils-ping net-tools

#RUN pecl install xdebug && docker-php-ext-enable xdebug

#ENTRYPOINT ["service","apache2","start"]