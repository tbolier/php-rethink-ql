# ReQL operation examples

*Work in progress document.*

## changes

```php
// In this example we set the time limit to 5 seconds before the process is terminated.
set_time_limit(5);

// The feed is an iterable cursor.
$feed = $this->r()
    ->table('tabletest')
    ->changes()
    ->run();

// We can do our operations meanwhile.
$this->insertDocument(1);
$this->insertDocument(2);
$this->insertDocument(3);
$this->insertDocument(4);
$this->insertDocument(5);

// When we iterate over the feed, it will print out the changes.
foreach ($feed as $change) {
   extract($change);

   print_r($old_val, $new_val);
}
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
