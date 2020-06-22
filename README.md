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
APP_ENV=dusk.local php artisan serve --port=8001 &
APP_ENV=dusk.local php artisan dusk --testdox
kill %1
```

See [our documentation for more information on using Dusk](docs/dusk.md).

### Dev Servers

To do local development, you will typically need to run three servers in the
background:

```sh
php artisan serve     # PHP+Laravel dev server
cd mix; npm run watch # Auto-generate mix assets
npm start             # Webpack+React dev server
```

To test locally, browse to http://localhost:8000/

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
