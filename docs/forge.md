# Forge Documentation for Abhayagiri Website

As of Friday, June 18, https://www.abhayagiri.org/ and
https://staging.abhayagiri.org/ are hosted on
[DigitalOcean](https://www.digitalocean.com/) with [Laravel
Forge](https://forge.laravel.com/) as the deployment manager.

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

Add the recipes in [`scripts/forge/recipes`](/scripts/forge/recipes) to the
Recipes tab in Forge. Each recipe should be run as the `root` user.  Be sure to
update `LIVEPATCH_TOKEN` in `install-canonical-livepatch.sh`.

Run each recipe for all servers.

## Nginx Configuration

For each website, add the following extra Nginx configuration after
`include forge-conf/www.abhayagiri.org/server/*;`:

```
    # BEGIN Extra Abhayagiri Nginx Configuration

    # Handle /th
    rewrite ^/th/?$ /index.php last;
    # Do not allow PHP under /media
    location ~ ^/media/.*\.phps?$ { deny all; }
    # Redirect /new/talks to /talks
    rewrite ^/new/(th/)?talks(.*)$ https://$server_name/$1talks$2 redirect;

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

Finally, change `www` to `staging` and `abhayagiri.sfo2.digitaloceanspaces.com`
to `abhayagiri-staging.sfo2.digitaloceanspaces.com` for staging.

## Environment Files

Copy the `.env` files from the secrets storage to the respective sites.

## Deploy Script

Copy the the following contents to the **Deploy Script** area for each site for
`abhayagiri-staging`:

```sh
export DEPLOY_PROJECT="staging.abhayagiri.org"
export DEPLOY_BRANCH="dev"
export DEPLOY_ENVIRONMENT="staging"

DEPLOY_SCRIPT_URL="https://raw.githubusercontent.com/abhayagiri/abhayagiri-website/$DEPLOY_BRANCH/scripts/forge/deploy.sh"

curl -s -o- "$DEPLOY_SCRIPT_URL" | bash
```

And for `abhayagiri-production`:

```sh
export DEPLOY_PROJECT="www.abhayagiri.org"
export DEPLOY_BRANCH="master"
export DEPLOY_ENVIRONMENT="production"

DEPLOY_SCRIPT_URL="https://raw.githubusercontent.com/abhayagiri/abhayagiri-website/$DEPLOY_BRANCH/scripts/forge/deploy.sh"

curl -s -o- "$DEPLOY_SCRIPT_URL" | bash
```

Start a deploy to `abhayagiri-staging`. Then if successful, deploy to
`abhayagiri-production`.

## Backup (`abhayagiri-production` only)

1. Ensure `rclone` is installed (see `install-update-packages.sh` recipe).

2. Copy `rclone.conf` from secrets storage to
   `/home/forge/.config/rclone/rclone.conf`, mode `0600`. If configuring from
   scratch, see the following documentation:

    - https://github.com/abhayagiri/abhayagiri-website/blob/dev/docs/digitalocean.md
    - https://github.com/abhayagiri/abhayagiri-website/blob/dev/docs/google-oauth.md
    - https://rclone.org/s3/#digitalocean-spaces
    - https://rclone.org/drive/#team-drives

3. Add a cron entry via [forge](https://forge.laravel.com/):

    - Command: `/bin/bash /home/forge/www.abhayagiri.org/scripts/forge/backup.sh`
    - User: `forge`
    - Frequency: Custom `30 1 * * *` (1:30am UTC)
