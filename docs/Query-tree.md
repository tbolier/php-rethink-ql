# Query tree guide

A simple RethinkDB query like:

```php
$r->db('test')->table('tabletest')->filter('{"title":"Test document"}')->count();
```

## Under the hood

First we nest all query operation objects. This is a too long dump for the example. So afterwards the `toArray` method is called to normalize the query.
This is how the message object looks like with the normalized query before we serialize it.

```php
TBolier\RethinkQL\Message\Message Object
(
    [queryType:TBolier\RethinkQL\Message\Message:private] => 1
    [query:TBolier\RethinkQL\Message\Message:private] => Array
        (
            [0] => 43
            [1] => Array
                (
                    [0] => Array
                        (
                            [0] => 39
                            [1] => Array
                                (
                                    [0] => Array
                                        (
                                            [0] => 15
                                            [1] => Array
                                                (
                                                    [0] => tabletest
                                                )

                                        )

                                    [1] => Array
                                        (
                                            [0] => 98
                                            [1] => Array
                                                (
                                                    [0] => {"title":"Test document"}
                                                )

                                        )

                                )

                        )

                )

        )

    [options:TBolier\RethinkQL\Message\Message:private] => TBolier\RethinkQL\Query\Options Object
        (
            [db:TBolier\RethinkQL\Query\Options:private] => Array
                (
                    [0] => 14
                    [1] => Array
                        (
                            [0] => test
                        )

                )

        )

)
```

Now we JSON serialize the Message object and we end up with a raw query that we send to the RethinkDB (server):
 
```php
[
  1,
  [
    43,
    [
      [
        39,
        [
          [
            15,
            [
              "tabletest"
            ]
          ],
          [
            98,
            [
              "{\"title\":\"Test document\"}"
            ]
          ]
        ]
      ]
    ]
  ],
  {
    "db": [
      14,
      [
        "test"
      ]
    ]
  }
]
```
