# Forge Documentation for Abhayagiri Website

As of Friday, June 18, https://www.abhayagiri.org/ and
https://staging.abhayagiri.org/ are hosted on
[DigitalOcean](https://www.digitalocean.com/) with [Laravel
Forge](https://forge.laravel.com/) as the deployment manager..

## Recipes

The following recipe is used to install any dependencies:

```sh
# Abhayagiri Website Install Dependencies on Forge
#
# Last Updated: 2019-08-12

# Update everything
apt-get update
DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -y

# Install PHP dependencies
apt-get install -y \
    php7.3 php7.3-bz2 php7.3-curl php7.3-gd php7.3-opcache \
    php7.3-mbstring php7.3-mysql php7.3-xml php7.3-zip

# Install rclone
curl https://rclone.org/install.sh | bash

# Install nvm, node and npm for forge
sudo -u forge -H bash -c '
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.34.0/install.sh | bash
    source /home/forge/.nvm/nvm.sh
    sudo -u forge -H /home/forge/.nvm/nvm install 12.4.0
    npm update -g --verbose
'
```

## Nginx Configuration

Add the following extra Nginx configuration after
`include forge-conf/www.abhayagiri.org/server/*;`:

```
    # BEGIN Extra Abhayagiri Nginx Configuration

    # Handle /th
    rewrite ^/th/?$ /index.php last;
    # Do not allow PHP under /media
    location ~ ^/media/.*\.phps?$ { deny all; }
    # Redirect /new/talks to /talks
    rewrite ^/new/(th/)?talks(.*)$ https://$server_name/$1talks$2 redirect;
    # React routes
    rewrite ^/(th/)?(gallery|talks|contact)(/.*)?$ /new/index.html last;

    # Proxy 20th Anniversary to DigitalOcean Spaces
    rewrite ^/20$ /20/ redirect;
    rewrite ^/20/$ /20/index.html redirect;
    location /20/ {
        proxy_pass https://abhayagiri.sfo2.digitaloceanspaces.com/media/discs/Abhayagiri%27s%2020th%20Anniversary/;
    }

    # Proxy /media to DigitalOcean Spaces
    rewrite ^/(media/.+)/$ https://$server_name/$1/index.html redirect;
    location /media/ {
        proxy_pass https://abhayagiri.sfo2.digitaloceanspaces.com/media/;
    }

    # END Extra Abhayagiri Nginx Configuration
```

In addition, in order to handle some legacy PHP, replace the line:

```
    location ~ \.php$ {
```

with:

```
    location /index.php {
```

Finally, to enable logging, replace the line:

```
    access_log off;

```

with:

```
    access_log /var/log/nginx/www.abhayagiri.org-access.log;
```

Change `www` to `staging` for staging.

## Deploy Script

The following is the deploy script for `www.abhayagiri.org`:

```sh
# Abhayagiri Website Deploy Script
#
# Last Updated: 2019-08-20
#
# The following recipes need to be installed prior to deploy:
#
#     install-abhayagiri-forge-dependencies
#
# Based on https://timleland.com/zero-downtime-laravel-forge-deploys/

# Stop script on any error
set -e

PROJECT="www.abhayagiri.org"
BRANCH="master"

# Ensure nvm/npm is in the environment
export NVM_DIR="$HOME/.nvm"
source "$NVM_DIR/nvm.sh"

# Remove old deployment folders
if [ -d "/home/forge/$PROJECT.old" ]; then
    rm -rf "/home/forge/$PROJECT.old"
fi
if [ -d "/home/forge/$PROJECT.new" ]; then
    rm -rf "/home/forge/$PROJECT.new"
fi

# Ensure a checkout
if ! [ -d "/home/forge/$PROJECT" ]; then
    echo "Project folder does not exist: $PROJECT"
    exit 1
fi

# Make a copy of the current folder
cp -a "/home/forge/$PROJECT" "/home/forge/$PROJECT.new"

# Update
cd "/home/forge/$PROJECT.new"
git pull origin "$BRANCH"
git checkout -f "$BRANCH"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader > /dev/null
php artisan optimize:clear
composer dump-autoload
php artisan route:cache

npm install > /dev/null 2>&1
npm run build > /dev/null 2>&1
( cd mix; npm install > /dev/null 2>&1 )
( cd mix; npm run production > /dev/null 2>&1 )

php artisan app:stamp

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
else
    echo "SENTRY_AUTH_TOKEN not found in .env"
fi

# Switch (downtime for microseconds)
mv "/home/forge/$PROJECT" "/home/forge/$PROJECT.old"
mv "/home/forge/$PROJECT.new" "/home/forge/$PROJECT"
cd "/home/forge/$PROJECT"

# Restart services
echo "" | sudo -S service php7.3-fpm reload

# Run migrations
php artisan migrate --force

# Restart any workers
php artisan queue:restart
```

For `staging.abhayagiri.org`, replace the two variables at the top with:

```sh
PROJECT="staging.abhayagiri.org"
BRANCH="dev"
```

## Configuration

### Server Details

- Database
    - Databases
        - Name: `abhayagiri_production` (production)
        - Name: `abhayagiri_staging` (staging)
        - User: `abhayagiri_production` (production)
        - User: `abhayagiri_staging` (staging)
- PHP
    - Max File Upload Size:
        - Megabytes: 4000
    - OPCache: Enable
- Scheduler
    - Frequency: Every Minute `* * * * *`
    - User: `forge`
    - Command: `php /home/forge/www.abhayagiri.org/artisan schedule:run` (production)
    - Command: `php /home/forge/staging.abhayagiri.org/artisan schedule:run` (staging)

### Site Details

- Deployment Branch
    - Branch: `master` (production)
    - Branch: `dev` (staging)
- Update Git Remote
    - Provider: GitHub
    - Repository: `abhayagiri/abhayagiri-website`
- SSL
    - LetsEncrypt
- Meta
    - Site Domain: `www.abhayagiri.org` (production)
    - Site Domain: `staging.abhayagiri.org` (staging)
    - Web Directory: `/public`
