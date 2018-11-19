# ReQL operation examples

*Work in progress document.*

## changes

Insert five documents and consume the changefeed.
```php
// In this example we set the time limit to 5 seconds before the process is terminated.
set_time_limit(5);

// The feed is an iterable cursor.
$feed = $this->r()
    ->table('tabletest')
    ->changes()
    ->run();

// We can do our operations meanwhile.
$res = $this->r()
    ->table('tabletest')
    ->insert([
        [
            'id' => 2,
            'title' => 'Test document 2',
            'description' => 'A document description.',
        ],
        [
            'id' => 2,
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
$feed = $this->r()
    ->table('tabletest')
    ->changes(['squash' => true])
    ->run();

$res = $this->r()
    ->table('tabletest')
    ->insert([
        [
            'id' => 1,
            'title' => 'Test document 1',
            'description' => 'A document description.',
        ]
    ])
    ->run();

$this->r()
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

## update           
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

## filter
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
