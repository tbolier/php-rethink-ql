# Research: query tree

A simple RethinkDB query like:

```php
$r->db('blog')->table('users')->filter('{name: 'Michel'}');
```

Will send this query to the RethinkDB socket.

Index 0, [0,1,2]
```php
[
    39, 
    [[15, [[14, ["blog"]], "users"]],
    {"name": "Michel"}]
]
```

Index 1, [Arguments]
```php
[
    [
        15,
        [[14, ["blog"]],
        "users"
    ]
]
```

Index 2, [0,1,2]
```php
[
    15,
    [[14, ["blog"]],
    "users"
]
```

Index 3, [Arguments]
```php
[
    [14, ["blog"]
]
```

Index 4, [0,1,2]
```php
[
    14,
    ["blog"]
]
```

This is how we build up the query under the hood.

```php
Query([
    39, 
    [
        Query([
            15,
            [
                Query([14, ["blog"])
            ],
            "users"
        ])
    ],
    {"name": "Michel"}]
])
```

A recursive `toArray` method is added to the queries objects, returning the full query as an array which we will send to RethinkDB.
