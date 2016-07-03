# Local setup

Setup Apache:

- Enable PHP
- Enable rewrite
- Point `DocumentRoot` to the `public` directory
- Add `AllowOverride All` to the `public` directory

Setup MySQL:

- Create a database

Copy `config/config.php.example` to `config/config.php` and edit.

Setup directories:

    mkdir -m 0777 public/ai-cache

Import development media and database:

    util/import-test-media.php
    util/import-test-db.php

