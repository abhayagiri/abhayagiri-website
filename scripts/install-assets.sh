#!/bin/bash
#
# Install Javascript and CSS Assets for Abhayagiri Website
#

set -e
cd "$(dirname "$0")/.."

php artisan vendor:publish \
    --provider="Backpack\CRUD\BackpackServiceProvider" --tag="minimum"
php artisan elfinder:publish

echo "Installing React NodeJS dependencies..."
npm install --silent

echo -n "Building React NodeJS assets..."
npm run build > /dev/null 2>&1
echo

echo "Installing Mix NodeJS dependencies..."
( cd mix && npm install --silent )

echo -n "Building Mix NodeJS assets..."
( cd mix && npm run production > /dev/null 2>&1 )
echo
