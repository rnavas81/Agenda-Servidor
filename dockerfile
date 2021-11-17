#Desacrga la imagen
FROM php:apache-buster

#Fichero para solucionar los problemas de rutas en Laravel
COPY ./000-default.conf /etc/apache2/sites-available/

RUN apt-get --yes update && apt-get --yes upgrade && apt --yes autoremove && apt-get --yes clean &&\
    # Instala Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer &&\
    # Instala las dependencias necesarias
    apt-get install -y libmcrypt-dev openssl zip unzip git &&\
    # Instala las necesidades para la persistencia de datos
    docker-php-ext-install mysqli pdo_mysql &&\
    docker-php-ext-enable mysqli pdo_mysql 
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    graphviz \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

# Recarga el vhost para aplicar los cambios necesarios
RUN a2enmod headers  &&\
    a2enmod rewrite  &&\
    service apache2 restart  &&\
    # Limpia los residuos de las instalaciones
    apt-get clean


# Directorio de trabajo
WORKDIR /var/www/html/

# Abre el puerto para el acceso
EXPOSE 80
