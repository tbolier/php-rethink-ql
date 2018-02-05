                                                 PHP-RETHINK-QL
                         A PHP RethinkDB driver for the RethinkDB query language (ReQL).
                                          License: Apache License 2.0

PHP-RETHINK-QL [![by](https://img.shields.io/badge/by-%40tbolier-ff69b4.svg?style=flat-square)](https://github.com/tbolier) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tbolier/php-rethink-ql/build-status/master)
========================

# License
PHP-RETHINK-QL is licensed under the terms of the Apache License 2.0 http://www.apache.org/licenses/LICENSE-2.0

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

For more information go to our docs section: [Getting started](docs/Getting-started.md)

# Supported ReQL Command API overview.

Below you will find a table with the supported ReQL command methods per release version.

| method | Version 1.0 | Version 2.0 |
| --- | :---: | :---: |
| **Accessing ReQL**
| r                      | ✓ | | 
| connection             | ✓ | | 
| close                  | ✓ | | 
| reconnect              | ✓ | | 
| use                    | ✓ | | 
| run                    | ✓ | | 
| runNoReply             | ✓ | | 
| changes                | ✓ | | 
| noreplyWait            | ✓ | | 
| server                 | ✓ | | 
| optArg                 | ✘ | |
| | | |
| **Cursors** 
| next                   | ✓ | | 
| for                    | ✓ | | 
| toList                 | ✓ | | 
| close                  | ✓ | | 
| | | | 
| **Manipulating databases** 
| dbCreate               | ✓ | | 
| dbDrop                 | ✓ | | 
| dbList                 | ✓ | | 
| | | |
| **Manipulating tables**
| tableCreate            | ✓ | | 
| tableDrop              | ✓ | | 
| tableList              | ✓ | | 
| indexCreate            | ✘ | | 
| indexDrop              | ✘ | | 
| indexList              | ✘ | | 
| indexRename            | ✘ | | 
| indexStatus            | ✘ | | 
| indexWait              | ✘ | | 
| | | |
| **Writing data**
| insert                 | ✓ | | 
| update                 | ✓ | | 
| replace                | ✓ | | 
| delete                 | ✓ | | 
| sync                   | ✘ | | 
| | | |
| **Selecting data** 
| db                     | ✓ | | 
| table                  | ✓ | | 
| get                    | ✓ | | 
| getAll                 | ✓ | | 
| between                | ✘ | | 
| filter                 | ✓ | | 
| Joins                  | ✘ | | 
| innerJoin              | ✘ | | 
| outerJoin              | ✘ | | 
| eqJoin                 | ✘ | | 
| zip                    | ✘ | | 
| | | |
| **Transformations** 
| map                    | ✘ | | 
| withFields             | ✘ | | 
| concatMap              | ✘ | | 
| orderBy                | ✘ | | 
| skip                   | ✘ | | 
| limit                  | ✘ | | 
| slice                  | ✘ | | 
| nth                    | ✘ | | 
| offsetsOf              | ✘ | | 
| isEmpty                | ✘ | | 
| union                  | ✘ | | 
| sample                 | ✘ | | 
| | | |
| **Aggregation** 
| group                  | ✘ | | 
| ungroup                | ✘ | | 
| reduce                 | ✘ | | 
| fold                   | ✘ | | 
| count                  | ✘ | | 
| sum                    | ✘ | | 
| avg                    | ✘ | | 
| min                    | ✘ | | 
| max                    | ✘ | | 
| distinct               | ✘ | | 
| contains               | ✘ | | 
| | | |
| **Document manipulation** 
| row                    | ✘ | | 
| pluck                  | ✘ | | 
| without                | ✘ | | 
| merge                  | ✘ | | 
| append                 | ✘ | | 
| prepend                | ✘ | | 
| difference             | ✘ | | 
| setInsert              | ✘ | | 
| setUnion               | ✘ | | 
| setIntersection        | ✘ | | 
| setDifference          | ✘ | | 
| () (bracket)           | ✘ | | 
| getField               | ✘ | | 
| hasFields              | ✘ | | 
| insertAt               | ✘ | | 
| spliceAt               | ✘ | | 
| deleteAt               | ✘ | | 
| changeAt               | ✘ | | 
| keys                   | ✘ | | 
| values                 | ✘ | | 
| literal                | ✘ | | 
| object                 | ✘ | | 
| String manipulation    | ✘ | | 
| match                  | ✘ | | 
| split                  | ✘ | | 
| upcase                 | ✘ | | 
| downcase               | ✘ | | 
| Math and logic         | ✘ | | 
| add                    | ✘ | | 
| sub                    | ✘ | | 
| mul                    | ✘ | | 
| div                    | ✘ | | 
| mod                    | ✘ | | 
| and                    | ✘ | | 
| or                     | ✘ | | 
| eq                     | ✘ | | 
| ne                     | ✘ | | 
| gt                     | ✘ | | 
| ge                     | ✘ | | 
| lt                     | ✘ | | 
| le                     | ✘ | | 
| not                    | ✘ | | 
| random                 | ✘ | | 
| round                  | ✘ | | 
| ceil                   | ✘ | | 
| floor                  | ✘ | | 
| | | |
| **Dates and times** 
| now                    | ✘ | | 
| time                   | ✘ | | 
| epochTime              | ✘ | | 
| ISO8601                | ✘ | | 
| inTimezone             | ✘ | | 
| timezone               | ✘ | | 
| during                 | ✘ | | 
| date                   | ✘ | | 
| timeOfDay              | ✘ | | 
| year                   | ✘ | | 
| month                  | ✘ | | 
| day                    | ✘ | | 
| dayOfWeek              | ✘ | | 
| dayOfYear              | ✘ | | 
| hours                  | ✘ | | 
| minutes                | ✘ | | 
| seconds                | ✘ | | 
| toISO8601              | ✘ | | 
| toEpochTime            | ✘ | | 
| | | |
| **Control structures** 
| array                  | ✘ | | 
| hashMap                | ✘ | | 
| args                   | ✘ | | 
| binary                 | ✘ | | 
| do                     | ✘ | | 
| branch                 | ✘ | | 
| forEach                | ✘ | | 
| range                  | ✘ | | 
| error                  | ✘ | | 
| default                | ✘ | | 
| expr                   | ✘ | | 
| js                     | ✘ | | 
| coerceTo               | ✘ | | 
| typeOf                 | ✘ | | 
| info                   | ✘ | | 
| json                   | ✘ | | 
| "toJsonString, toJSON" | ✘ | | 
| http                   | ✘ | | 
| uuid                   | ✘ | | 
| Geospatial commands    | ✘ | | 
| circle                 | ✘ | | 
| distance               | ✘ | | 
| fill                   | ✘ | | 
| geojson                | ✘ | | 
| toGeojson              | ✘ | | 
| getIntersecting        | ✘ | | 
| getNearest             | ✘ | | 
| includes               | ✘ | | 
| intersects             | ✘ | | 
| line                   | ✘ | | 
| point                  | ✘ | | 
| polygon                | ✘ | | 
| polygonSub             | ✘ | | 
| | | |
| **Administration**
| grant                  | ✘ | | 
| config                 | ✘ | | 
| rebalance              | ✘ | | 
| reconfigure            | ✘ | | 
| status                 | ✘ | | 
| wait                   | ✘ | | 
