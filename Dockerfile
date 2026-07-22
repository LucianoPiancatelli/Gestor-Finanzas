FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL / PDO
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para que funcione el enrutamiento MVC (.htaccess)
RUN a2enmod rewrite

# Apuntar el DocumentRoot de Apache a la carpeta /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Copiar todo el código del proyecto al contenedor
COPY . /var/www/html/

EXPOSE 80
