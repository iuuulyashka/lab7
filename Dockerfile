FROM php:8.2-fpm

# Обновляем пакеты и устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    wget \
    gnupg \
    ca-certificates \
    librdkafka-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем rdkafka
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

# Устанавливаем Composer - используем несколько источников
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php || \
    (wget -O composer-setup.php https://getcomposer.org/installer && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php)

WORKDIR /var/www/html

COPY . .

CMD ["php-fpm"]