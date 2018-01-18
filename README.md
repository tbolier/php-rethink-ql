                                 PHP-RETHINK-QL
            A PHP client driver for the RethinkDB query language (ReQL).
                            License: Apache License 2.0

PHP-RETHINK-QL is licensed under the terms of the Apache License 2.0 http://www.apache.org/licenses/LICENSE-2.0

This library uses some parts of code of the original PHP-RQL library of Daniel Mewes (https://github.com/danielmewes/php-rql).
This code have stated the modified changes that can be found in the PHPDoc above those specific classes.

# Requirements

## RethinkDB version

This library supports the RethinkDB version V1_0 protocol and RethinkDB release >=2.3.0.

## PHP

PHP version >=7.1

# Example

```PHP
<?php

use TBolier\RethinkQL\Connection\Registry;
use TBolier\RethinkQL\Document\Manager;

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

$manager = new Manager($registry->getConnection('default_connection'));
$manager->createQueryBuilder()
    ->table('tableName')
    ->insert([
        [
            'documentId' => 1,
            'title' => 'Test document',
            'description' => 'My first document.'  
        ],    
    ])
    ->execute();;

$manager->createQueryBuilder()
    ->table('tableName')
    ->filter([
        [
            'title' => 'Test document',
        ],
    ])
    ->count()
    ->execute();

# You can also change from the default database
$manager->selectDatabase('demoDB-2');
```
