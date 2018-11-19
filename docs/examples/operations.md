# ReQL operation examples

## between

Insert three documents, select `between` 1 and 2, assert the result.

```php
// We can do our operations meanwhile.
$res = $r
    ->table('tabletest')
    ->insert([
        [
            'id' => 1,
            'title' => 'Test document 1',
            'description' => 'A document description.',
        ],
        [
            'id' => 2,
            'title' => 'Test document 2',
            'description' => 'A document description.',
        ],
        [
            'id' => 3,
            'title' => 'Test document 3',
            'description' => 'A document description.',
        ],
    ])
    ->run();

/** @var Cursor $cursor */
$cursor = $r
    ->table('tabletest')
    ->between(1, 2)
    ->run();

echo $cursor->count() === 2 ? 'equals' : 'different';
foreach ($cursor as $document) {
    print_r($document);
}
```

## changes
Insert five documents and consume the `changes` afterwards.
```php
// In this example we set the time limit to 5 seconds before the process is terminated.
set_time_limit(5);

// The feed is an iterable cursor.
$feed = $r
    ->table('tabletest')
    ->changes()
    ->run();

// We can do our operations meanwhile.
$res = $r
    ->table('tabletest')
    ->insert([
        [
            'id' => 2,
            'title' => 'Test document 2',
            'description' => 'A document description.',
        ],
        [
            'id' => 1,
            'title' => 'Test document 1',
            'description' => 'A document description.',
        ],
    ])
    ->run();

// When we iterate over the feed, it will print out the changes.
foreach ($feed as $change) {
   extract($change);
   print_r($old_val, $new_val);
}
```

#### changes with options
Insert one document, update it, and consume the change feed with a squashed cursor.
For all possible options, please visit the Java documentation [here](https://rethinkdb.com/api/java/changes/).
```php
$feed = $r
    ->table('tabletest')
    ->changes(['squash' => true])
    ->run();

$res = $r
    ->table('tabletest')
    ->insert([
        [
            'id' => 1,
            'title' => 'Test document 1',
            'description' => 'A document description.',
        ]
    ])
    ->run();

$r
    ->table('tabletest')
    ->filter(['id' => 1])
    ->update(['description' => 'cool!'])
    ->run();

$change = $feed->current();
print_r($change);
```

## tableCreate
```php
$r->db()
  ->tableCreate('Table')
  ->run();
```

## insert     
Insert one or more documents.      
```php
$r->table('tableName')
  ->insert([
      'documentId' => 1,
      'title' => 'Test document 1',
      'description' => 'A document description.'
  ])
  ->run();

$r->table('tableName')
  ->insert([
      [
          'documentId' => 2,
          'title' => 'Test document 2',
          'description' => 'A document description.'  
      ],
      [
          'documentId' => 3,
          'title' => 'Test document 3',
          'description' => 'A document description.'  
      ],  
  ])
  ->run();
```

## update
Update one or more documents.
```php
$r->table('tableName')
  ->filter([
      [
          'title' => 'A document description.',
      ],    
  ])
  ->update([
      [
          'title' => 'Updated document description.',
      ],    
  ])
  ->run();
```

## filter
Filter and count the results.
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

## sync
Save writes to permanent storage.

```php
$r->table('tableName')
  ->sync()
  ->run();
```