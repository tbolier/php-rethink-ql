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
