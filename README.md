# Abhayagiri Website

## Quickstart

Install the [prerequisites](docs/prerequisites.md), then:

```sh
git clone https://github.com/abhayagiri/abhayagiri-website
cd abhayagiri-website
php first-time-setup
php artisan serve
```

Then, point your browser to http://localhost:8000/.

To login to Mahapanel, go to: http://localhost:8000/mahapanel_bypass?email=root@localhost.

## Test

```
vendor/bin/codecept run
```

## More

- [Prerequisites](docs/prerequisites.md)
- [Docker Setup](docs/docker.md)
- [Server Deployment](docs/deploy.md)
- [Google OAuth Setup](docs/google-oauth.md)
