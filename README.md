# Rethink Connect

WIP: An ODM approach to RethinkDB.

# Usage

```php
<?php

use TBolier\RethinkConnect\Connection\Registry;
use TBolier\RethinkConnect\Document\Manager;

$connections = [
    'default_connection' => [
        'host' => 'localhost',
        'port' => 28015,
        'user' => 'demo',
        'password' => 'demo',
        'timeout' => 5, 
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
    ]);
```