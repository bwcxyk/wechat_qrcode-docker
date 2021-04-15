#FROM alpine:3.10
FROM php:7.2.30-fpm-alpine3.11

RUN set -x \
    && echo "http://mirrors.aliyun.com/alpine/latest-stable/main/" > /etc/apk/repositories \
    && echo "http://mirrors.aliyun.com/alpine/latest-stable/community/" >> /etc/apk/repositories \
    && apk update \
    && apk add nginx  \
    && apk add curl bash \
    && apk add php7-mysqli php7-pdo_mysql php7-mbstring php7-json php7-zlib php7-gd php7-intl php7-session php7-memcached php7-curl php7-posix php7-fileinfo php7-simplexml php7-opcache php7-tokenizer php7-ctype php7-bcmath php7-openssl php7-dom php7-iconv php7-zip php7-pcntl php7-xmlwriter \
    && mkdir /run/nginx

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY run.sh /run.sh
#COPY index.php /var/www/index.php
COPY weAppCode.php /var/www/qrcode/weAppCode.php

# 若使用root权限启动nginx, 开启gzip压缩, 需修改配置如下
RUN sed 's/user nginx;/user root;/g' /etc/nginx/nginx.conf | sed 's/#gzip on;/gzip on;/g' > /tmp/nginx.conf \
    && mv /tmp/nginx.conf /etc/nginx/nginx.conf

# 开启opcache
RUN sed 's/;opcache.enable=1/opcache.enable=1/g' /etc/php7/php.ini \
    && sed 's/;opcache.validate_timestamps=1/opcache.validate_timestamps=0/g' \
    && sed 's/;opcache.memory_consumption=128/opcache.memory_consumption=128/g' \
    && sed 's/;opcache.interned_strings_buffer=8/opcache.interned_strings_buffer=8/g' \
    && sed 's/;opcache.max_accelerated_files=10000/opcache.max_accelerated_files=10000/g' > /tmp/php.ini \
	&& mv /tmp/php.ini /etc/php7/php.ini

WORKDIR /var/www/qrcode

EXPOSE 80 443

CMD ["/run.sh"]
