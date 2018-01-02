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
vendor/bin/codecept run
```

## Frontend Development

```sh
npm start
```

Browse: http://localhost:9000/new

### Testing

You first need to set up a few things:

**TODO simplify**

```sh
php artisan serve &
npm run build
php artisan stamp
node tests/selenium-setup.js
kill %1
```

Then:

```sh
npm test
```

## More

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Sauce Labs](docs/saucelabs.md)
- [Server Deployment](https://github.com/abhayagiri/abhayagiri-website-deploy)
