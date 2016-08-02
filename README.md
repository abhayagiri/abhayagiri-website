## Local setup

Setup Apache:

- Enable PHP
- Enable rewrite
- Point `DocumentRoot` to the `public` directory
- Add `AllowOverride All` to the `public` directory

Install Composer modules:

    composer install

Setup MySQL:

- Create a database

Copy `config/config.php.example` to `config/config.php` and edit.

Setup directories:

    mkdir -m 0777 logs public/ai-cache tmp

Import development media and database:

    util/import-test-media.php
    util/import-test-db.php

## Testing

    vendor/bin/codecept run

## Mac OS X Setup

Install MySQL:

    brew install mysql

Install phantomjs:

    brew install phantomjs

Needed after every boot:

    mysql.server start
    phantomjs --webdriver=4444 # in a new Terminal tab/window

## Deploy

Staging:

    vendor/bin/dep deploy-import staging

Production:

    vendor/bin/dep deploy production
