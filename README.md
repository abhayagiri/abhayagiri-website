# Abhayagiri Website

## Quickstart

Install the [prerequisites](docs/prerequisites.md), then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
php artisan serve
```

Point your browser to http://localhost:8000/.

To login to Mahapanel, go to: http://localhost:8000/mahapanel_bypass?email=root@localhost.

## React Frontend

```sh
npm start
```

Point your browser to http://localhost:9000/new.

## Test Backend

```sh
vendor/bin/codecept run
```

## Test Frontend

```sh
php artisan serve &
npm run build
php artisan stamp
node tests/selenium-setup.js
npm test
kill %1
```

## Test Frontend Using SauceLabs

Get a SauceLabs account and install [Sauce Connect](https://wiki.saucelabs.com/display/DOCS/Sauce+Connect+Proxy). Then,

```sh
export SAUCE_USERNAME=abhayagiri
export SAUCE_ACCESS_KEY=...
sc -u $SAUCE_USERNAME -k $SAUCE_ACCESS_KEY &
php artisan serve &
npm run build
php artisan stamp
npm run test-saucelabs
kill %1 %2
```

## More

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Server Deployment](docs/deploy.md)
- [Google OAuth Setup](docs/google-oauth.md)
