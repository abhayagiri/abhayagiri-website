
## Install/Deploy

Staging:

```sh
vendor/bin/dep deploy staging
```

Production:

```sh
vendor/bin/dep deploy production
```

## Schedule

Add to crontab via `crontab -e`:
```
* * * * * /usr/local/php56/bin/php $HOME/www.abhayagiri.org/current/artisan schedule:run > /dev/null 2>&1
```

## Import Database on Staging

```sh
vendor/bin/dep deploy:import-database staging
```
