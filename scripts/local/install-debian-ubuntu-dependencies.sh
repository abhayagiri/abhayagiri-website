#!/bin/bash
#
# Install Debian / Ubuntu Local Dependencies for Abhyagiri Website
#
# For Debian Stretch/Buster, Ubuntu 16.04+
#

set -e
cd "$(dirname "$0")/../.."

#
# Git and others...
#

sudo apt-get update
sudo apt-get install -y apt-transport-https ca-certificates curl dirmngr git \
    lsb-release software-properties-common unzip wget zip

#
# PHP 7.3
#

sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
sudo bash -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
sudo apt-get update
sudo apt-get install -y \
    php7.3 php7.3-bz2 php7.3-curl php7.3-dev php7.3-fpm php7.3-gd \
    php7.3-opcache php7.3-mbstring php7.3-mysql php7.3-sqlite3 php7.3-xml \
    php7.3-zip
sudo update-alternatives --set php /usr/bin/php7.3
sudo update-alternatives --set phar /usr/bin/phar7.3
sudo update-alternatives --set phar.phar /usr/bin/phar.phar7.3
sudo update-alternatives --set phpize /usr/bin/phpize7.3
sudo update-alternatives --set php-config /usr/bin/php-config7.3

#
# MySQL 5.7
#

MYSQL_APT_CONFIG_DEB="$(mktemp --suffix .deb)"
chmod 644 "$MYSQL_APT_CONFIG_DEB"
wget -q -O "$MYSQL_APT_CONFIG_DEB" \
    "https://repo.mysql.com/mysql-apt-config_0.8.14-1_all.deb"
cat <<EOF | sudo debconf-set-selections
mysql-apt-config mysql-apt-config/select-server select mysql-5.7
mysql-apt-config mysql-apt-config/enable-repo select mysql-5.7-dmr
EOF
sudo DEBIAN_FRONTEND=noninteractive dpkg -i "$MYSQL_APT_CONFIG_DEB"
rm -f "$MYSQL_APT_CONFIG_DEB"
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y \
    -o Dpkg::Options::="--force-confdef" \
    -o Dpkg::Options::="--force-confold" \
    mysql-server

#
# Google Chrome
#

wget -O - -q https://dl-ssl.google.com/linux/linux_signing_key.pub \
    | sudo apt-key add -
sudo bash -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" \
    > /etc/apt/sources.list.d/google-chrome.list'
sudo apt-get update
sudo apt-get install -y google-chrome-stable

#
# Nginx
#

sudo apt-get install -y nginx-full

#
# Composer
#

wget -O - -q https://getcomposer.org/installer \
    | sudo php7.3 -- --install-dir=/usr/local/bin --filename=composer

#
# nvm, Node.js and npm:
#

if [ "$(id -u)" = "0" ]; then
    echo "nvm should be installed as a regular user" > /dev/stderr
else
    curl -s -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.1/install.sh | bash
    source "$HOME/.nvm/nvm.sh" || true
    nvm install --latest-npm
fi
