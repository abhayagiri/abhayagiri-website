## Prerequisites

### Linux

```
apt-get install -y apache2 php5 mysql-client mysql-server
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Mac OS X

```
brew tap homebrew/dupes
brew tap homebrew/versions
brew tap homebrew/homebrew-php
brew install mysql php56
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

Needed after every boot:

```
mysql.server start
```

## Install

```
git clone git@gitlab.com:abhayagiri/www.abhayagiri.org.git
cd www.abhayagiri.org
composer install
cp .env.example .env
php artisan key:generate
```

Import development media and database, and fix permissions:

```
php artisan command:import-media
php artisan command:import-database
php artisan command:fix-local-dirs
```

## Try

Point your browser to: http://localhost/

Login to Mahapanel via: http://localhost/mahapanel_bypass?email=root@localhost

## Testing

```
vendor/bin/codecept run
```

## Local Setup

Setup Apache:

- Enable PHP
- Enable rewrite
- Point `DocumentRoot` to the `public` directory
- Add `AllowOverride All` to the `public` directory

Setup MySQL:

- Create a database with permissions.

## Deploy

Staging:

```
vendor/bin/dep deploy-import staging
```

Production:

```
vendor/bin/dep deploy production
```
