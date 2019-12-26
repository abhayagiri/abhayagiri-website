# Abhayagiri Website

## Development

We recommended that you [use Homestead to setup your local development
environment](docs/homestead.md).

## Testing

To run the [Laravel Unit and Feature](https://laravel.com/docs/6.x/testing)
tests:

```sh
vendor/bin/phpunit
```

To run the [Laravel Dusk](https://laravel.com/docs/6.x/dusk) tests:

```sh
# Start a web server for Dusk
php artisan serve --port=8001 --env=dusk.local > /dev/null 2>&1 &
# Run the Dusk tests
php artisan dusk
# Stop the Dusk web server
kill $(lsof -t -i:8001)
```

You can also run all the tests with the following artisan command:

```sh
php artisan test
```

## Dev Servers

For a better development experience, you may need to run a dev server:

```sh
php artisan serve     # PHP+Laravel dev server
cd mix; npm run watch # Auto-generate mix assets
npm start             # Webpack+React dev server
```

Then, browse to:

- PHP+Laravel: http://localhost:8000/
- PHP+Laravel+Backpack: http://localhost:8000/admin
- Webpack+React: http://localhost:9000/

The Webpack+React dev server will proxy unhandled requests to the PHP+Laravel
dev server, so make sure it's running.

## Upgrade Woes

If you're getting errors following a recent change, try doing the following:

- `php artisan optimize:clear`
- `php artisan dusk:install`
- `php artisan migrate:fresh --seed`
- Clear browser cache

## More Information

- [Homestead Setup](docs/homestead.md)
- [Direct Setup](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Sauce Labs](docs/saucelabs.md)
- [Laravel Forge Configuration](docs/forge.md)
