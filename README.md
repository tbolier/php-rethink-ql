                                                 PHP-RETHINK-QL
                         A PHP RethinkDB driver for the RethinkDB query language (ReQL).
                                          License: Apache License 2.0

PHP-RETHINK-QL [![by](https://img.shields.io/badge/by-%40tbolier-ff69b4.svg?style=flat-square)](https://github.com/tbolier)
========================

# License
PHP-RETHINK-QL is licensed under the terms of the Apache License 2.0 http://www.apache.org/licenses/LICENSE-2.0

This library uses some parts of code of the original `PHP-RQL`  library of Daniel Mewes (https://github.com/danielmewes/php-rql).
The classes that contain parts of code from `PHP-RQL` have a PHPDoc on top of the class, stating the reference and changes refering to this parts of code taken from `PHP-RQL`.

# Description

A new clean and solid RethinkDB driver for PHP, focused on clean code with SOLID principles in mind.

Unfortunately the original PHP-RQL driver is no longer is no longer actively maintained and patched. That's why we have started this new PHP RethinkDB driver with the goal to create an easy to understand driver that can be improved and maintained by the community.

Feel free to fork this code and contribute.

# Requirements

## RethinkDB version

This library supports the RethinkDB release `>=2.3.0` and protocol version `V1_0`.
Earliers version of RethinkDB are not supported at this moment.

## PHP

PHP version `>=7.1`

# Examples

Multiple connections can be injected into the connection `Registry`.
Create multiple document `Manager` per connection.

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

An insert example:            
```php
$r->table('tableName')
  ->insert([
      [
          'documentId' => 1,
          'title' => 'Test document',
          'description' => 'My first document.'  
      ],    
  ])
  ->run();
```

An update example:            
```php
$r->table('tableName')
  ->filter([
      [
          'title' => 'Test document',
      ],    
  ])
  ->update([
      [
          'title' => 'Updated document',
      ],    
  ])
  ->run();
```

A filter and count example:
```php
$r->table('tableName')
  ->filter([
      [
          'title' => 'Test document',
      ],
  ])
  ->count()
  ->run();
```
