#!/bin/sh

# Environment
sed -i -e "s|###appId###|$appId|g" weAppCode.php && \
sed -i -e "s|###weapp_secret###|$weapp_secret|g" weAppCode.php && \
sed -i -e "s|###imgpath###|$imgpath|g" weAppCode.php

# cron
# del tmpfile
tee -a /var/spool/cron/crontabs/root << EOF
0 2 * * * find /var/www/qrcode/tmsimg/pages* -type f -mtime +30 |xargs rm -f
EOF
# Start crond
/usr/sbin/crond

# Start php and nginx 
while :
do

  runningNginx=$(ps -ef |grep "nginx" |grep -v "grep" | wc -l)
  if [ "$runningNginx" -eq 0 ] ; then
    echo "Nginx service was not started. Starting now." 
    /usr/sbin/nginx
  fi

  runningPHP=$(ps -ef |grep "php-fpm" |grep -v "grep" | wc -l)
  if [ "$runningPHP" -eq 0 ] ; then
    echo "PHP service was not started. Starting now." 
    /usr/local/sbin/php-fpm
  fi

  sleep 60
done
