FROM composer:latest AS vendor
WORKDIR /vendor
RUN composer require dodo-it/entity-generator

FROM php:8.2-cli-bookworm

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /app

COPY index.php .
COPY --from=vendor vendor/ .

RUN mkdir entities
VOLUME /app/entities

ENV MYSQL_HOSTNAME=db
ENV MYSQL_DATABASE=database
ENV MYSQL_USERNAME=username
ENV MYSQL_PASSWORD=password
# ENV BASECLASS=
# ENV ENTITY_NAMESPACE=

SHELL ["/bin/bash", "-c"]
ENTRYPOINT ["php", "index.php"]
CMD ["php", "index.php"]
