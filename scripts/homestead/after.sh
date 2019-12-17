#!/bin/bash
#
# Homestead Post-Provisioning for Abhayagiri Website
#

set -e
cd "$(dirname "$0")/../.."

# Restart avahi to ensure abhayagiri.local name is resolved
sudo systemctl restart avahi-daemon.service

# Ensure everything is upgraded
sudo apt-get update
DEBIAN_FRONTEND=noninteractive sudo -E apt-get dist-upgrade -y \
    -o "Dpkg::Options::=--force-confdef" \
    -o "Dpkg::Options::=--force-confold"

# Install required packages
DEBIAN_FRONTEND=noninteractive sudo -E apt-get install -y \
    php7.3-bz2 zip

# Install Google Chrome
wget -O - -q https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo bash -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list'
sudo apt-get update
sudo apt-get install -y google-chrome-stable

# Configure Nginx
sudo cp -f scripts/homestead/abhayagiri.local /etc/nginx/sites-available/abhayagiri.local
sudo systemctl restart nginx.service

# Install nvm, node and npm
curl -s -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.1/install.sh | bash
source "$HOME/.nvm/nvm.sh"
nvm install --latest-npm

# Setup and install project dependencies
bash scripts/install-local.sh

# Ensure we have permissions to create the database
echo "GRANT ALL on abhayagiri.* TO 'abhayagiri'@'localhost' IDENTIFIED BY 'abhayagiri'; FLUSH PRIVILEGES;" | mysql

# Migrate database with seeds
php artisan migrate:fresh --seed

# Set APP_URL to abhayagiri.local
perl -pi -e 's/^APP_URL=.*$/APP_URL=http:\/\/abhayagiri.local/' .env
