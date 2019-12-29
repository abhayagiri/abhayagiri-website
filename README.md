# Abhayagiri Website

This project is for the website running at https://www.abhayagiri.org/. It uses
[Laravel](https://laravel.com/), [Backpack](https://backpackforlaravel.com/),
[Vue.js](https://vuejs.org/), [Bootstrap](https://getbootstrap.com/) and many
other projects.

## Local Development

If you wish to work on the website, we recommend that you [use Homestead to
setup your local development environment](docs/homestead.md). In brief:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
cp .env.example .env
cp Homestead.yaml.example Homestead.yaml
composer install
vagrant up
```

After Vagrant finishes installing, point your browser to
http://abhayagiri.local/.

You can alternatively [setup your environment manually](docs/local-dev.md).

### Unit and Feature Testing

To run the [Laravel Unit and Feature](https://laravel.com/docs/6.x/testing)
tests:

```sh
vendor/bin/phpunit
```

### Dusk (Browser) Testing

To run the [Laravel Dusk](https://laravel.com/docs/6.x/dusk) tests:

```sh
php artisan dusk
```

**Note**: this will use the existing local web server and _overwrite/clobber_
the database specified in `.env`. You can alternatively [run Laravel Dusk run
with it's own web server and database by using a separate
environment](docs/dusk.md).

### Run All Tests

If you've configured Laravel Dusk, you can run all the tests with:

```sh
php artisan test
```

### Dev Servers

For a better development experience, you may want to run one or more dev servers
to quickly rebuild assets:

```sh
php artisan serve     # PHP+Laravel dev server
cd mix; npm run watch # Auto-generate mix assets
npm start             # Webpack+React dev server
```

Then, browse to:

- PHP+Laravel: http://localhost:8000/
- Webpack+React: http://localhost:9000/

### Upgrade Woes

If you're getting errors due a recent change, try the following to reset things:

```sh
php artisan optimize:clear
scripts/install-local.sh
php artisan migrate:fresh --seed
```

Also be sure to clear your browser's cache.

## Hosting Configuration/Documentation

The following services are used on the production webserver:

- [Algolia Setup](docs/algolia.md)
- [Digital Ocean Setup](docs/digitalocean.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Laravel Forge Setup](docs/forge.md)
