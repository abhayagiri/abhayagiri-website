# Abhayagiri Website

## Quickstart

Install the [prerequisites](docs/prerequisites.md), then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
```

## Backend Development

```sh
php artisan serve
```

Browse: http://localhost:8000/

Admin: http://localhost:8000/admin

### Testing

```sh
vendor/bin/phpunit      # Unit tests
vendor/bin/codecept run # Functional and API tests
```

## Frontend Development

```sh
npm start
```

Browse: http://localhost:9000/new

### End-to-End Testing

As of 2019-06-16, end-to-end testing via [nightwatch](https://nightwatchjs.org/) is borked. A workaround:

```sh
node_modules/.bin/selenium-standalone install
npm run build
php artisan serve &
node_modules/.bin/selenium-standalone start &
# Wait for both the server and selenium to start...
node_modules/.bin/nightwatch -c tests/nightwatch/nightwatch.local-no-autostart.js
kill %1 %2
```

## More

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Sauce Labs](docs/saucelabs.md)
- [Server Deployment](https://github.com/abhayagiri/abhayagiri-website-deploy)
