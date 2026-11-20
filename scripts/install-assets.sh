#!/bin/bash
#
# Install Javascript and CSS Assets for Abhayagiri Website
#

set -e
cd "$(dirname "$0")/.."

php artisan vendor:publish \
    --provider="Backpack\CRUD\BackpackServiceProvider" --tag="minimum"
php artisan elfinder:publish

echo "Installing Node dependencies..."
npm ci --ignore-scripts --silent

echo -n "Building Node JS/CSS assets..."
npm run production > /dev/null 2>&1
echo
