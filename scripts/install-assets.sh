#!/bin/bash
#
# Install Javascript and CSS Assets for Abhayagiri Website
#

set -e
cd "$(dirname "$0")/.."

php artisan vendor:publish \
    --provider="Backpack\CRUD\BackpackServiceProvider" --tag="minimum"
php artisan elfinder:publish

echo "Installing Mix NodeJS dependencies..."
npm install --silent

echo -n "Building Mix NodeJS assets..."
npm run production > /dev/null 2>&1
echo

echo "Installing React NodeJS dependencies..."
( cd new && npm install --silent )

echo -n "Building React NodeJS assets..."
( cd new && npm run production > /dev/null 2>&1 )
echo
