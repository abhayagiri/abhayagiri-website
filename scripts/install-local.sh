#!/bin/bash
#
# Setup/Install Local (Dev/Test) Dependencies for Abhyagiri Website
#

set -e
cd "$(dirname "$0")/.."

hostname="$(hostname -s)"
if [ "$hostname" = "abhayagiri-production" ] || [ "$hostname" = "abhayagiri-staging" ]; then
    echo "This script should not be run on production or staging."
    exit 1
fi

# Ensure .env exists
if ! test -f .env; then
    cp .env.example .env
fi

# Install Composer dependencies
composer install

# Ensure the APP_KEY is set
if ! grep -q "^APP_KEY=b" .env; then
    php artisan key:generate
fi

# Create .env.dusk.local
if ! test -f .env.dusk.local; then
    cp .env.dusk.local.example .env.dusk.local
fi

# Install Chrome driver for Dusk.
if which google-chrome-stable > /dev/null; then
    chrome_version="$(google-chrome-stable --version | sed 's/[^0-9.]//g' | sed 's/\..*//g')"
else
    chrome_version=""
fi
php artisan dusk:chrome-driver $chrome_version

# Install a subset of the media files to be served locally
php artisan app:import-media

# Install Javascript/CSS dependencies and assets
bash scripts/install-assets.sh
