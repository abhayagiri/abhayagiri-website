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

## Frontend Development

```sh
npm start
```

Browse: http://localhost:9000/new

### Testing

```sh
vendor/bin/phpunit      # Unit tests
vendor/bin/codecept run # Functional and API tests
npm test                # React unit tests
php artisan dusk        # Browser (end-to-end) tests
```

## More

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Google OAuth Setup](docs/google-oauth.md)
- [Sauce Labs](docs/saucelabs.md)
- [Server Deployment](https://github.com/abhayagiri/abhayagiri-website-deploy)
