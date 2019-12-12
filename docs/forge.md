# Forge Documentation for Abhayagiri Website

As of Friday, June 18, https://www.abhayagiri.org/ and
https://staging.abhayagiri.org/ are hosted on
[DigitalOcean](https://www.digitalocean.com/) with [Laravel
Forge](https://forge.laravel.com/) as the deployment manager..

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
ENVIRONMENT="production"

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

php artisan vendor:publish --provider="Backpack\CRUD\BackpackServiceProvider" --tag="minimum"
php artisan elfinder:publish

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
    sentry-cli releases deploys "$VERSION" new -e "$ENVIRONMENT"
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

For `staging.abhayagiri.org`, replace the three variables at the top with:

```sh
PROJECT="staging.abhayagiri.org"
BRANCH="dev"
ENVIRONMENT="staging"
```

## Backup Script (`abhayagiri_production` only)

1. Install `rclone` if not already installed (should be installed by dependency
   installer from above).

2. `mkdir /home/forge/backup`.

3. Copy the backup script (see below) to `/home/forge/backup/backup.sh`, mode
   `0755`.

4. Copy `rclone.conf` from LastPass to `/home/forge/.config/rclone/rclone.conf`,
   mode `0600`. If building from scratch, see the following documentation:

    - https://github.com/abhayagiri/abhayagiri-website/blob/dev/docs/digitalocean.md
    - https://github.com/abhayagiri/abhayagiri-website/blob/dev/docs/google-oauth.md
    - https://rclone.org/s3/#digitalocean-spaces
    - https://rclone.org/drive/#team-drives

5. Add a cron entry via [forge](https://forge.laravel.com/):

    - Command: `/bin/bash /home/forge/backup/backup.sh`
    - User: `forge`
    - Frequency: Custom `30 1 * * *` (1:30am UTC)

```sh
#!/bin/bash
#
# Abhayagiri Website Backup Script
#
# Last Updated: 2019-09-12

cd "$(dirname "$0")"
BASE_DIR="$(pwd)"

WEBSITE="www.abhayagiri.org"
RCLONE_MEDIA_SRC="do-spaces:abhayagiri"
RCLONE_BACKUP_DEST="gd-backup:"
RCLONE_OPTIONS="--verbose --tpslimit 20 --fast-list"
REPOSITORY_URL="https://github.com/abhayagiri/abhayagiri-website.git"

ACCESS_LOG_PATH="/var/log/nginx/$WEBSITE-access.log"
ERROR_LOG_PATH="/var/log/nginx/$WEBSITE-error.log"
LARAVEL_LOG_DIR="$HOME/$WEBSITE/storage/logs"
BACKUP_PATH="$HOME/$WEBSITE/storage/backups/abhayagiri-private-database-latest.sql.bz2"
BACKUP_LOG_PATH="$(mktemp)"

function copy-dated {
  if ! test -f "$1"; then
    echo "Warning: $1 does not exist"
    return
  fi
  log_path="$2/$(date -r "$1" "+%Y/%m/%Y%m%d-$3")"
  mkdir -p "$(dirname "$log_path")"
  cp -vf "$1" "$log_path"
}

(

  set -e

  # Prepare Local Files

  if ! test -d src; then
    git clone "$REPOSITORY_URL" src
  fi
  ( cd src && git fetch --all && git reset --hard origin/master)

  copy-dated "$BACKUP_PATH" "$BASE_DIR/databases" abhayagiri-database.sql.bz2

  copy-dated "$ACCESS_LOG_PATH"    "$BASE_DIR/logs/access"  access.log
  copy-dated "$ACCESS_LOG_PATH.1"  "$BASE_DIR/logs/access"  access.log
  copy-dated "$ERROR_LOG_PATH"     "$BASE_DIR/logs/error"   error.log
  copy-dated "$ERROR_LOG_PATH.1"   "$BASE_DIR/logs/error"   error.log
  copy-dated "$LARAVEL_LOG_DIR/laravel-$(date "+%Y-%m-%d").log" \
                                   "$BASE_DIR/logs/laravel" laravel.log
  copy-dated "$LARAVEL_LOG_DIR/laravel-$(date -d yesterday "+%Y-%m-%d").log" \
                                   "$BASE_DIR/logs/laravel" laravel.log

  # Copy

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/src/"           "$RCLONE_BACKUP_DEST/src/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/databases/"     "$RCLONE_BACKUP_DEST/databases/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/logs/"          "$RCLONE_BACKUP_DEST/logs/"

  rclone sync $RCLONE_OPTIONS \
    "$RCLONE_MEDIA_SRC/media/" "$RCLONE_BACKUP_DEST/media/"

) >> "$BACKUP_LOG_PATH" 2>&1

rclone copyto \
  "$BACKUP_LOG_PATH" \
  "$RCLONE_BACKUP_DEST/logs/backups/$(date "+%Y/%m/%Y%m%d-%H%M%S")-backup.log"

rm -f "$BACKUP_LOG_PATH"
```
