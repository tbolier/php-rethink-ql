# Tests

We value quality. Because everyone can contribute we have created some guidelines.

## Configuration

First you need to install RethinkDB.

Then it's necessary to duplicate the standard configuration, and replace default values in `test/config.php`.

```sh
cp test/config.php.dist test/config.php
```

## Run

```sh
composer update

vendor/bin/phpunit
```

Thank you!
