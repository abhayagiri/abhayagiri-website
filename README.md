# Abhayagiri Website

This project is for the website running at https://www.abhayagiri.org/. It uses
[Laravel](https://laravel.com/), [Backpack](https://backpackforlaravel.com/),
[Vue.js](https://vuejs.org/), [Bootstrap](https://getbootstrap.com/) and many
other projects.

## Local Development

If you wish to work on the website, we recommend that you use Herd to
setup your local development environment.

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
composer install
```

### Backpack PRO Credentials

Abhayagiri uses [backpack PRO](https://backpackforlaravel.com/) for its admin panel. You'll be asked to enter the backpack PRO credentials during the composer installation. This credentials can be found here: https://backpackforlaravel.com/user/tokens

```sh
php artisan key:generate
php artisan migrate --seed
# Install backpack assets
php artisan backpack:install

npm install
npm run dev
```

### Unit and Feature Testing

To run the [Laravel Unit and Feature](https://laravel.com/docs/6.x/testing)
tests:

```sh
vendor/bin/phpunit
```

### Cypress Integration Testing

To run the [Cypress](https://on.cypress.io/) tests:

```sh
APP_ENV=test php artisan serve --port=8001 > /dev/null 2>&1 &
APP_ENV=test php artisan migrate:fresh --seed
$(npm bin)/cypress run
kill %1
```

For more information about Cypress, see the [official
documentation](https://on.cypress.io/).

### Upgrade Woes

If you're getting errors due a recent change, try the following to reset things:

```sh
php artisan optimize:clear
composer dump-autoload
scripts/install-local.sh
php artisan migrate:fresh --seed
```

Also be sure to clear your browser's cache.

## Hosting Configuration/Documentation

The following services are used on the production webserver:

* [Algolia Setup](docs/algolia.md)
* [Digital Ocean Setup](docs/digitalocean.md)
* [Google OAuth Setup](docs/google-oauth.md)
* [Laravel Forge Setup](docs/forge.md)
* [Backups](./docs/backups.md)
