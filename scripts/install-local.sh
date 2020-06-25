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

# Install a subset of the media files to be served locally
php artisan app:import-media

# Install Javascript/CSS dependencies and assets
bash scripts/install-assets.sh
