# Abhayagiri Website

## Quickstart

Install the [prerequisites](docs/prerequisites.md), then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
```

## Development

```sh
php artisan serve # PHP+Laravel dev server
npm start         # Webpack+React dev server
```

Then, browse to:

- PHP+Laravel: http://localhost:8000/
- PHP+Laravel+Backpack: http://localhost:8000/admin
- Webpack+React: http://localhost:9000/

The Webpack+React dev server will proxy unhandled requests to the PHP+Laravel
dev server, so make sure it's running.

## Testing

```sh
php artisan test        # Run all the tests
```

Or, more explicitly:

```
vendor/bin/phpunit      # Unit tests
vendor/bin/codecept run # Functional and API tests
npm test                # React unit tests
php artisan dusk        # Browser (Dusk end-to-end) tests
```

The Browser tests require that the PHP+Laravel dev server is running.

## More Information

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Sauce Labs](docs/saucelabs.md)
- [Server Deployment](https://github.com/abhayagiri/abhayagiri-website-deploy)
