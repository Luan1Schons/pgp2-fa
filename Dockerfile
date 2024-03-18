# Use the official PHP image as base
FROM php:8-apache

# Install required packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        libssl-dev \
        gnupg \
        libgpg-error-dev \
        libassuan-dev \
        libgpgme11-dev \
        wget \
    && rm -rf /var/lib/apt/lists/*

# Download and build GPGME
RUN wget https://www.gnupg.org/ftp/gcrypt/gpgme/gpgme-1.23.2.tar.bz2 \
    && tar xfvj gpgme-1.23.2.tar.bz2 \
    && cd gpgme-1.23.2 \
    && ./configure \
    && make \
    && make install \
    && cd .. \
    && rm -rf gpgme-1.23.2 \
    && rm gpgme-1.23.2.tar.bz2

# Install the PHP extension
RUN pecl install gnupg \
    && echo "extension=gnupg.so" > /usr/local/etc/php/conf.d/gnupg.ini
