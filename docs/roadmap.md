# Roadmap



## current and next versions

| method | Version 1.0 | Version 1.1 | Version x |
| --- | :---: | :---: | :---: |
| **Accessing ReQL**
| r                      | ✓ | ✓ | |
| connection             | ✓ | ✓ | | 
| close                  | ✓ | ✓ | |
| reconnect              | ✓ | ✓ | |
| use                    | ✓ | ✓ | | 
| run                    | ✓ | ✓ | |
| runNoReply             | ✓ | ✓ | |
| changes                | ✓ | ✓ | |
| noreplyWait            | ✓ | ✓ | |
| server                 | ✓ | ✓ | |
| optArg                 | ✘ | ✘ | |
| | | | |
| **Cursors** 
| next                   | ✓ | ✓ | |
| for                    | ✓ | ✓ | |
| toList                 | ✓ | ✓ | |
| close                  | ✓ | ✓ | |
| | | |  |
| **Manipulating databases** 
| dbCreate               | ✓ | ✓ | |
| dbDrop                 | ✓ | ✓ | |
| dbList                 | ✓ | ✓ | |
| | | | |
| **Manipulating tables**
| tableCreate            | ✓ | ✓ | |
| tableDrop              | ✓ | ✓ | |
| tableList              | ✓ | ✓ | |
| indexCreate            | ✘ | ✓ | |
| indexDrop              | ✘ | ✓ | | 
| indexList              | ✘ | ✓ | |
| indexRename            | ✘ | ✓ | |
| indexStatus            | ✘ | ✘ | |
| indexWait              | ✘ | ✘ | |
| | | | |
| **Writing data**
| insert                 | ✓ | ✓ | |
| update                 | ✓ | ✓ | |
| replace                | ✓ | ✓ | |
| delete                 | ✓ | ✓ | |
| sync                   | ✘ | ✘ | |
| | | | |
| **Selecting data** 
| db                     | ✓ | ✓ | |
| table                  | ✓ | ✓ | |
| get                    | ✓ | ✓ | |
| getAll                 | ✘ | ✓ | |
| between                | ✘ | ✘ | |
| filter                 | ✓ | ✓ | |
| Joins                  | ✘ | ✘ | |
| innerJoin              | ✘ | ✘ | |
| outerJoin              | ✘ | ✘ | |
| eqJoin                 | ✘ | ✘ | |
| zip                    | ✘ | ✘ | |
| | | | |
| **Transformations** 
| map                    | ✘ | ✘ | |
| withFields             | ✘ | ✘ | |
| concatMap              | ✘ | ✘ | |
| orderBy                | ✘ | ✓ | |
| skip                   | ✘ | ✓ | |
| limit                  | ✘ | ✓ | |
| slice                  | ✘ | ✘ | |
| nth                    | ✘ | ✘ | |
| offsetsOf              | ✘ | ✘ | |
| isEmpty                | ✘ | ✓ | |
| union                  | ✘ | ✘ | |
| sample                 | ✘ | ✘ | |
| | | | |
| **Aggregation** 
| group                  | ✘ | ✘ | |
| ungroup                | ✘ | ✘ | |
| reduce                 | ✘ | ✘ | |
| fold                   | ✘ | ✘ | |
| count                  | ✓ | ✓ | |
| sum                    | ✘ | ✘ | |
| avg                    | ✘ | ✘ | |
| min                    | ✘ | ✘ | |
| max                    | ✘ | ✘ | |
| distinct               | ✘ | ✘ | |
| contains               | ✘ | ✘ | |
| | | | |
| **Document manipulation** 
| row                    | ✘ | ✘ | |
| pluck                  | ✘ | ✘ | |
| without                | ✘ | ✘ | |
| merge                  | ✘ | ✘ | |
| append                 | ✘ | ✘ | |
| prepend                | ✘ | ✘ | |
| difference             | ✘ | ✘ | |
| setInsert              | ✘ | ✘ | |
| setUnion               | ✘ | ✘ | |
| setIntersection        | ✘ | ✘ | |
| setDifference          | ✘ | ✘ | |
| () (bracket)           | ✘ | ✘ | |
| getField               | ✘ | ✘ | |
| hasFields              | ✘ | ✘ | |
| insertAt               | ✘ | ✘ | |
| spliceAt               | ✘ | ✘ | |
| deleteAt               | ✘ | ✘ | |
| changeAt               | ✘ | ✘ | |
| keys                   | ✘ | ✘ | |
| values                 | ✘ | ✘ | |
| literal                | ✘ | ✘ | |
| object                 | ✘ | ✘ | |
| String manipulation    | ✘ | ✘ | |
| match                  | ✘ | ✘ | |
| split                  | ✘ | ✘ | |
| upcase                 | ✘ | ✘ | |
| downcase               | ✘ | ✘ | |
| Math and logic         | ✘ | ✘ | |
| add                    | ✘ | ✘ | |
| sub                    | ✘ | ✘ | |
| mul                    | ✘ | ✘ | |
| div                    | ✘ | ✘ | |
| mod                    | ✘ | ✘ | |
| and                    | ✘ | ✘ | |
| or                     | ✘ | ✘ | |
| eq                     | ✘ | ✘ | |
| ne                     | ✘ | ✘ | |
| gt                     | ✘ | ✘ | |
| ge                     | ✘ | ✘ | |
| lt                     | ✘ | ✘ | |
| le                     | ✘ | ✘ | |
| not                    | ✘ | ✘ | |
| random                 | ✘ | ✘ | |
| round                  | ✘ | ✘ | |
| ceil                   | ✘ | ✘ | |
| floor                  | ✘ | ✘ | |
| | | | |
| **Dates and times** 
| now                    | ✘ | ✘ | |
| time                   | ✘ | ✘ | |
| epochTime              | ✘ | ✘ | |
| ISO8601                | ✘ | ✘ | |
| inTimezone             | ✘ | ✘ | |
| timezone               | ✘ | ✘ | |
| during                 | ✘ | ✘ | |
| date                   | ✘ | ✘ | |
| timeOfDay              | ✘ | ✘ | |
| year                   | ✘ | ✘ | |
| month                  | ✘ | ✘ | |
| day                    | ✘ | ✘ | |
| dayOfWeek              | ✘ | ✘ | |
| dayOfYear              | ✘ | ✘ | |
| hours                  | ✘ | ✘ | |
| minutes                | ✘ | ✘ | |
| seconds                | ✘ | ✘ | |
| toISO8601              | ✘ | ✘ | |
| toEpochTime            | ✘ | ✘ | |
| | | | |
| **Control structures** 
| array                  | ✘ | ✘ | | 
| hashMap                | ✘ | ✘ | | 
| args                   | ✘ | ✘ | | 
| binary                 | ✘ | ✘ | | 
| do                     | ✘ | ✘ | | 
| branch                 | ✘ | ✘ | | 
| forEach                | ✘ | ✘ | | 
| range                  | ✘ | ✘ | | 
| error                  | ✘ | ✘ | | 
| default                | ✘ | ✘ | | 
| expr                   | ✘ | ✘ | | 
| js                     | ✘ | ✘ | | 
| coerceTo               | ✘ | ✘ | | 
| typeOf                 | ✘ | ✘ | | 
| info                   | ✘ | ✘ | | 
| json                   | ✘ | ✘ | | 
| "toJsonString, toJSON" | ✘ | ✘ | | 
| http                   | ✘ | ✘ | | 
| uuid                   | ✘ | ✘ | | 
| Geospatial commands    | ✘ | ✘ | | 
| circle                 | ✘ | ✘ | | 
| distance               | ✘ | ✘ | | 
| fill                   | ✘ | ✘ | | 
| geojson                | ✘ | ✘ | | 
| toGeojson              | ✘ | ✘ | | 
| getIntersecting        | ✘ | ✘ | | 
| getNearest             | ✘ | ✘ | | 
| includes               | ✘ | ✘ | | 
| intersects             | ✘ | ✘ | | 
| line                   | ✘ | ✘ | | 
| point                  | ✘ | ✘ | | 
| polygon                | ✘ | ✘ | | 
| polygonSub             | ✘ | ✘ | | 
| | | | |
| **Administration**
| grant                  | ✘ | ✘ | | 
| config                 | ✘ | ✘ | | 
| rebalance              | ✘ | ✘ | | 
| reconfigure            | ✘ | ✘ | | 
| status                 | ✘ | ✘ | | 
| wait                   | ✘ | ✘ | | 
