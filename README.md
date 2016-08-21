## Local setup

Setup Apache:

- Enable PHP
- Enable rewrite
- Point `DocumentRoot` to the `public` directory
- Add `AllowOverride All` to the `public` directory

Install Composer modules:

    composer install

Setup MySQL:

- Create a database with permissions.

Copy `.env.example` to `.env` and edit.

Fix local development directories:

    php command:fix-local-dirs

Import development media and database:

    php command:import-test-media
    php command:import-test-db

Then Mahapanel login via: http://localhost/mahapanel_bypass?email=root@localhost

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
