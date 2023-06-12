FROM onprintshop/php-fpm:apache

ARG HOSTNAME

RUN cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default-backup.conf

RUN sed -i -e 's/localhostname/'$HOSTNAME'/' /etc/apache2/sites-available/000-default.conf