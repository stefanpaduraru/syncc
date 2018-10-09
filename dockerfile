FROM nimmis/apache

MAINTAINER Stefan P <stefan@paduraru.com>

# disable interactive functions
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
apt-get install -y git-all && \
apt-get install -y php libapache2-mod-php  \
php-fpm php-cli \
php-apcu php-intl php-json php-curl && \
rm -rf /var/lib/apt/lists/* && \
mkdir /home/stefan && \
mkdir /home/stefan/html && \
cd /tmp && \
git clone https://github.com/stefanpaduraru/syncc.git && \
cp -r /tmp/syncc/* /var/www/html