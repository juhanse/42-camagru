FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    msmtp \
    msmtp-mta \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

RUN echo "account default" > /etc/msmtprc \
    && echo "host mailhog" >> /etc/msmtprc \
    && echo "port 1025" >> /etc/msmtprc \
    && echo "from no-reply@camagru.com" >> /etc/msmtprc \
    && chmod 600 /etc/msmtprc \
    && chown www-data:www-data /etc/msmtprc \
    && echo 'sendmail_path = "/usr/bin/msmtp -t -i"' > /usr/local/etc/php/conf.d/mail.ini

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html