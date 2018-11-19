    PHP-RETHINK-QL
    A PHP RethinkDB driver for the RethinkDB query language (ReQL).
    License: Apache License 2.0

PHP-RETHINK-QL [![by](https://img.shields.io/badge/by-%40tbolier-blue.svg)](https://github.com/tbolier) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/build-status/master)
========================

# License
PHP-RETHINK-QL is licensed under the terms of the [Apache License 2.0](LICENSE.md)

# Description

A new clean and solid RethinkDB driver for PHP, focused on clean code with SOLID principles in mind.

Unfortunately the original PHP-RQL driver is no longer is no longer actively maintained and patched. That's why we have started this new PHP RethinkDB driver with the goal to create an easy to understand driver that can be improved and maintained by the community.

# Requirements

## RethinkDB version

This library supports the RethinkDB release `>=2.3.0` and protocol version `V1_0`.
Earliers version of RethinkDB are not supported at this moment.

## PHP

PHP version `>=7.1`

## Supported ReQL Command API overview.

In the release [roadmap](docs/roadmap.md) you will find a table with the currently and future supported ReQL command methods.

# Getting started

Multiple connections can be injected into the connection `Registry`.
Create the Rethink driver object by injecting a `Connection` object into it.

```php
<?php
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\Connection\Registry;

$connections = [
    'default_connection' => [
        'host' => 'localhost',
        'port' => 28015,
        'default_db' => 'demoDB',
        'user' => 'demo',
        'password' => 'demo',
        'timeout' => 5,
        'timeout_stream' => 10,
    ],
];

$registry = new Registry($connections);

$r = new Rethink($registry->getConnection('default_connection'));

// Now you can connect to RethinkDB.
$r->connection()->connect();
```

The driver class `Rethink` has a default database defined in the connection options. However you can always switch database if needed.
```php
$r->use('demoDB-2');
```

The driver class `Rethink` has an API Interface that supports the ReQL domain-specific language (DSL).

A create table example:
```php
$r->db()
  ->tableCreate('Table')
  ->run();
```

For more examples about executing queries go to our docs section: [Getting started](docs/getting-started.md)

## Contributing

Please read the [contributing guidelines](docs/contributing.md) if you would like to contribute to this project.

## Discussions and chat

You can find us at Gitter.im in the `rethinkdb-php` room at https://gitter.im/rethinkdb-php/Lobby

## Author and collaborators

* **Timon Bolier** - *Author and collaborator* - [tbolier](https://github.com/tbolier)
* **Michel Maas** - *Collaborator* - [AxaliaN](https://github.com/AxaliaN)
* **Jérémy** - *Collaborator* - [Th3Mouk](https://github.com/Th3Mouk)

See also the list of [contributors](https://github.com/tbolier/php-rethink-ql/contributors) who participated in this project.
