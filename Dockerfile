# Dockerfile

FROM php:8.3-fpm

# Instala dependências
RUN apt-get update && apt-get install -y \
  git unzip curl libicu-dev libzip-dev libxml2-dev libonig-dev \
  libpng-dev libjpeg-dev libfreetype6-dev libssl-dev libcurl4-openssl-dev \
  && docker-php-ext-install pdo pdo_pgsql zip intl opcache xml

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o projeto
COPY . /var/www/html
WORKDIR /var/www/html

# Instala dependências do Symfony
RUN composer install --no-dev --optimize-autoloader

# Dá permissão à pasta de cache e logs
RUN chown -R www-data:www-data var

# Exponha a porta do PHP-FPM (caso queira expor diretamente)
EXPOSE 8000

# Rodar o Symfony com servidor embutido em produção
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
