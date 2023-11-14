FROM php:8.1.18-fpm
WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html

RUN apt-get update -y && apt-get install -y curl zip unzip sendmail libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        default-mysql-client \
        net-tools \
        inetutils-ping \
        netcat \
        && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
        && docker-php-ext-install gd

RUN apt-get install -y git
RUN apt-get install -y zip libzip-dev \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip \
  && docker-php-ext-install exif && docker-php-ext-enable exif

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli pdo pdo_mysql
RUN docker-php-ext-install pcntl

RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

##################
# Custom Aliases #
##################

# Small hack for me to simplify chmod command needed on adding new files from within the container
# to prevent Permission Denied Issues
RUN echo "alias autoload='composer dumpautoload'" >> ~/.bashrc

COPY . .

ENTRYPOINT [ ".docker/php/entrypoint.sh" ]