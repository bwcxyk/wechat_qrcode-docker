FROM php:8.2-fpm-alpine

RUN set -ex \
  && sed -i 's#https\?://dl-cdn.alpinelinux.org/alpine#https://mirrors.tuna.tsinghua.edu.cn/alpine#g' /etc/apk/repositories \
  && apk update \
  && apk add --no-cache nginx  \
  && apk add --no-cache curl bash \
  && apk add --no-cache php-json php-curl php-fileinfo php-openssl

COPY default.conf /etc/nginx/conf.d/default.conf
COPY run.sh /run.sh
COPY weAppCode.php /var/www/qrcode/weAppCode.php

RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf
RUN chown -R www-data.www-data /var/www

WORKDIR /var/www/qrcode

EXPOSE 80 443

CMD ["/run.sh"]
