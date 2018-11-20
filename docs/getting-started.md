# Getting started

*Work in progress document.*

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

The driver class `Rethink` has an API Interface that supports the ReQL domain-specific language (DSL).

## Examples

### Operations

Examples on ReQL operations can be [found here](examples/operations.md).