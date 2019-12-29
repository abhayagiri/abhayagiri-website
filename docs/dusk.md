# Laravel Dusk Documentation for Abhayagiri Website

To run the [Laravel Dusk](https://laravel.com/docs/6.x/dusk) tests:

```sh
php artisan dusk
```

However, the above command will use the existing local web server and
_overwrite/clobber_ the database specified in `.env`. To avoid this problem,
you'll need to create a separate environment for running Dusk.

First create and edit `.env.dusk.local`:

```sh
cp .env.dusk.local.example .env.dusk.local
```

The example configuration from `.env.dusk.local.example` utilizes a local web
server running at port 8001 and a local MySQL database named `abhayagiri_dusk`.

To start the local web server, be sure to specify the `dusk.local` environment:

```sh
APP_ENV=dusk.local nohup php artisan serve --port=8001 > /dev/null 2>&1 &
```

To run the Dusk tests, also be sure to specify the `dusk.local` environment:

```sh
APP_ENV=dusk.local php artisan dusk
```

When you're done testing, you can stop the web server:

```sh
kill %1
```
