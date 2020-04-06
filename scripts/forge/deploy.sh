#!/bin/bash
#
# Forge Deploy Script for Abhayagiri Website
#
# https://github.com/abhayagiri/abhayagiri-website/blob/dev/scripts/forge/deploy.sh
#
# Loosely based on https://timleland.com/zero-downtime-laravel-forge-deploys/

# Stop script on any error
set -e

if [ -z "$DEPLOY_PROJECT" ] || [ -z "$DEPLOY_BRANCH" ] || [ -z "$DEPLOY_ENVIRONMENT" ]; then
    echo "This must be run with the DEPLOY_* environment variables set."
    exit 1
fi

echo "Deploy to $DEPLOY_ENVIRONMENT on branch $DEPLOY_BRANCH started on $(date)"

# Install nvm, node and npm
curl -s -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.1/install.sh | bash > /dev/null
source /home/forge/.nvm/nvm.sh
nvm install --latest-npm 12.13.1 > /dev/null 2>&1

# Remove old deployment folders
if [ -d "$HOME/$DEPLOY_PROJECT.old" ]; then
    rm -rf "$HOME/$DEPLOY_PROJECT.old"
fi
if [ -d "$HOME/$DEPLOY_PROJECT.new" ]; then
    rm -rf "$HOME/$DEPLOY_PROJECT.new"
fi

# Ensure a checkout
if ! [ -d "$HOME/$DEPLOY_PROJECT" ]; then
    echo "Project folder does not exist: $DEPLOY_PROJECT"
    exit 1
fi

# Make a copy of the current folder
cp -a "$HOME/$DEPLOY_PROJECT" "$HOME/$DEPLOY_PROJECT.new"

# Update
cd "$HOME/$DEPLOY_PROJECT.new"
git fetch origin "$DEPLOY_BRANCH"
git reset --hard "origin/$DEPLOY_BRANCH"

# Install Composer dependencies
composer install --no-dev --no-interaction --prefer-dist

# Reset any cached files
php artisan optimize:clear

# Ensure the autoloader is present
composer dump-autoload --optimize

# Set the routing cache
php artisan route:cache

# Create the application stamp
php artisan app:stamp

# Install Javascript/CSS dependencies and assets
bash scripts/install-assets.sh

# Switch! (downtime for microseconds...)
mv "$HOME/$DEPLOY_PROJECT" "$HOME/$DEPLOY_PROJECT.old"
mv "$HOME/$DEPLOY_PROJECT.new" "$HOME/$DEPLOY_PROJECT"
cd "$HOME/$DEPLOY_PROJECT"

# Restart services
echo "" | sudo -S service php7.3-fpm reload

# Run migrations
php artisan migrate --force

# Restart any workers
php artisan queue:restart

# Create a Sentry release
SENTRY_AUTH_TOKEN="$(cat .env | \
    grep '^SENTRY_AUTH_TOKEN=...' | \
    sed 's/^SENTRY_AUTH_TOKEN=//')"
if [ "$SENTRY_AUTH_TOKEN" != "" ]; then
    # Install or upgrade @sentry/cli
    npm install --global @sentry/cli
    export SENTRY_AUTH_TOKEN
    export SENTRY_ORG=abhayagiri
    VERSION="$(sentry-cli releases propose-version)"
    sentry-cli releases new -p abhayagiri-website "$VERSION"
    sentry-cli releases set-commits --auto "$VERSION"
    sentry-cli releases deploys "$VERSION" new -e "$DEPLOY_ENVIRONMENT"
else
    echo "SENTRY_AUTH_TOKEN not found in .env"
fi
