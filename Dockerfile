FROM php:8.2-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar extensiones de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurar Apache correctamente para PHP
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    echo "<FilesMatch \.php$>" > /etc/apache2/conf-available/php.conf && \
    echo "    SetHandler application/x-httpd-php" >> /etc/apache2/conf-available/php.conf && \
    echo "</FilesMatch>" >> /etc/apache2/conf-available/php.conf && \
    echo "DirectoryIndex index.php index.html" >> /etc/apache2/conf-available/php.conf && \
    a2enconf php

# Configurar permisos para .htaccess
RUN echo "<Directory /var/www/html>" > /etc/apache2/conf-available/docker-php.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/conf-available/docker-php.conf && \
    echo "    AllowOverride All" >> /etc/apache2/conf-available/docker-php.conf && \
    echo "    Require all granted" >> /etc/apache2/conf-available/docker-php.conf && \
    echo "</Directory>" >> /etc/apache2/conf-available/docker-php.conf

# Crear directorio para sesiones y dar permisos
RUN mkdir -p /var/lib/php/sessions && \
    chown www-data:www-data /var/lib/php/sessions && \
    chmod 700 /var/lib/php/sessions

# Configurar PHP para usar ese directorio de sesiones
RUN echo "session.save_path = \"/var/lib/php/sessions\"" > /usr/local/etc/php/conf.d/sessions.ini

# Copiar los archivos de la aplicación
COPY . /var/www/html/

# Crear archivo de test PHP
RUN echo "<?php phpinfo(); ?>" > /var/www/html/test.php

# Exponer puerto
EXPOSE 80

# Salud de contenedor
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
