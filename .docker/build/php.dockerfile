FROM webdevops/php-nginx-dev:7.3-alpine

RUN apk add php7-memcached php7-pdo php7-pgsql php7-pdo_pgsql tcpdump

EXPOSE 80
EXPOSE 9000
EXPOSE 443
