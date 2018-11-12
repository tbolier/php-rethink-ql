# Roadmap

Please note: The future release is version 2.0.
Please check the milestone to see it's progress:
https://github.com/tbolier/php-rethink-ql/issues?utf8=%E2%9C%93&q=milestone%3A%22Version+2.0%22+

## Currently supported features of ReQL API

| method | Version 1.0 | Version 1.1 | Version 1.2 | Version 1.3 (Current) |Version 2.0 (Future) |
| --- | :---: | :---: | :---: | :---: | :---: |
| **Accessing ReQL**
| r                      | ✓ | ✓ | ✓ | ✓ | ✓ |
| connection             | ✓ | ✓ | ✓ | ✓ | ✓ |
| close                  | ✓ | ✓ | ✓ | ✓ | ✓ |
| reconnect              | ✓ | ✓ | ✓ | ✓ | ✓ |
| use                    | ✓ | ✓ | ✓ | ✓ | ✓ |
| run                    | ✓ | ✓ | ✓ | ✓ | ✓ |
| runNoReply             | ✓ | ✓ | ✓ | ✓ | ✓ |
| changes                |   |   |   |   | ✓ |
| noreplyWait            | ✓ | ✓ | ✓ | ✓ | ✓ |
| server                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| optArg                 |   |   |   |   |   |
| | | | | | |
| **Cursors** 
| next                   | ✓ | ✓ | ✓ | ✓ | ✓ |
| for                    | ✓ | ✓ | ✓ | ✓ | ✓ |
| toList                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| close                  | ✓ | ✓ | ✓ | ✓ | ✓ |
| | | | | | |
| **Manipulating databases** 
| dbCreate               | ✓ | ✓ | ✓ | ✓ | ✓ |
| dbDrop                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| dbList                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| | | | | | |
| **Manipulating tables**
| tableCreate            | ✓ | ✓ | ✓ | ✓ | ✓ |
| tableDrop              | ✓ | ✓ | ✓ | ✓ | ✓ |
| tableList              | ✓ | ✓ | ✓ | ✓ | ✓ |
| indexCreate            |   | ✓ | ✓ | ✓ | ✓ |
| indexDrop              |   | ✓ | ✓ | ✓ | ✓ |
| indexList              |   | ✓ | ✓ | ✓ | ✓ |
| indexRename            |   | ✓ | ✓ | ✓ | ✓ |
| indexStatus            |   |   |   |   | ✓ |
| indexWait              |   |   |   |   | ✓ |
| | | | | | |
| **Writing data**
| insert                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| update                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| replace                | ✓ | ✓ | ✓ | ✓ | ✓ |
| delete                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| sync                   |   |   |   |   | ✓ |
| | | | | | |
| **Selecting data** 
| db                     | ✓ | ✓ | ✓ | ✓ | ✓ |
| table                  | ✓ | ✓ | ✓ | ✓ | ✓ |
| get                    | ✓ | ✓ | ✓ | ✓ | ✓ |
| getAll                 |   | ✓ | ✓ | ✓ | ✓ |
| between                |   |   |   | ✓ | ✓ |
| filter                 | ✓ | ✓ | ✓ | ✓ | ✓ |
| Joins                  |   |   |   |   | ✓ |
| innerJoin              |   |   |   |   | ✓ |
| outerJoin              |   |   |   |   | ✓ |
| eqJoin                 |   |   |   |   | ✓ |
| zip                    |   |   |   |   | ✓ |
| | | | | | |
| **Transformations** 
| map                    |   |   |   |   | ✓ |
| withFields             |   |   |   |   | ✓ |
| concatMap              |   |   |   |   | ✓ |
| orderBy                |   | ✓ | ✓ | ✓ | ✓ |
| skip                   |   | ✓ | ✓ | ✓ | ✓ |
| limit                  |   | ✓ | ✓ | ✓ | ✓ |
| slice                  |   |   |   |   | ✓ |
| nth                    |   |   |   |   | ✓ |
| offsetsOf              |   |   |   |   | ✓ |
| isEmpty                |   | ✓ | ✓ | ✓ | ✓ |
| union                  |   |   |   |   | ✓ |
| sample                 |   |   |   |   | ✓ |
| | | | | | |
| **Aggregation** 
| group                  |   |   | ✓ | ✓ | ✓ |
| ungroup                |   |   | ✓ | ✓ | ✓ |
| reduce                 |   |   |   |   | ✓ |
| fold                   |   |   |   |   | ✓ |
| count                  | ✓ | ✓ | ✓ | ✓ | ✓ |
| sum                    |   | ✓ | ✓ | ✓ | ✓ |
| avg                    |   | ✓ | ✓ | ✓ | ✓ |
| min                    |   | ✓ | ✓ | ✓ | ✓ |
| max                    |   | ✓ | ✓ | ✓ | ✓ |
| distinct               |   |   |   |   | ✓ |
| contains               |   |   |   |   | ✓ |
| | | | | | |
| **Document manipulation** 
| row                    |   |   | ✓ | ✓ | ✓ |
| pluck                  |   |   |   | ✓ | ✓ |
| without                |   |   |   | ✓ | ✓ |
| merge                  |   |   |   |   | ✓ |
| append                 |   |   |   |   | ✓ |
| prepend                |   |   |   |   | ✓ |
| difference             |   |   |   |   | ✓ |
| setInsert              |   |   |   |   | ✓ |
| setUnion               |   |   |   |   | ✓ |
| setIntersection        |   |   |   |   | ✓ |
| setDifference          |   |   |   |   | ✓ |
| () (bracket)           |   |   |   |   | ✓ |
| getField               |   |   |   | ✓ | ✓ |
| hasFields              |   |   |   | ✓ | ✓ |
| insertAt               |   |   |   |   | ✓ |
| spliceAt               |   |   |   |   | ✓ |
| deleteAt               |   |   |   |   | ✓ |
| changeAt               |   |   |   |   | ✓ |
| keys                   |   |   |   | ✓ | ✓ |
| values                 |   |   |   | ✓ | ✓ |
| literal                |   |   |   |   | ✓ |
| object                 |   |   |   |   | ✓ |
| | | | | | |
| **String manipulation** 
| match                  |   |   |   |   |   |
| split                  |   |   |   |   |   |
| upcase                 |   |   |   |   |   |
| downcase               |   |   |   |   |   |
| | | | | | |
| **Math and logic** 
| add                    |   |   |   |   |   |
| sub                    |   |   |   |   |   |
| mul                    |   |   |   |   |   |
| div                    |   |   |   |   |   |
| mod                    |   |   |   |   |   |
| and                    |   |   | ✓ | ✓ | ✓ |
| or                     |   |   | ✓ | ✓ | ✓ |
| eq                     |   |   | ✓ | ✓ | ✓ |
| ne                     |   |   | ✓ | ✓ | ✓ |
| gt                     |   |   | ✓ | ✓ | ✓ |
| ge                     |   |   |   | ✓ | ✓ |
| lt                     |   |   | ✓ | ✓ | ✓ |
| le                     |   |   |   | ✓ | ✓ |
| not                    |   |   |   | ✓ | ✓ |
| random                 |   |   |   |   | ✓ |
| round                  |   |   |   |   | ✓ |
| ceil                   |   |   |   |   | ✓ |
| floor                  |   |   |   |   | ✓ |
| | | | | | |
| **Dates and times** 
| now                    |   |   |   |   | ✓ |
| time                   |   |   |   |   | ✓ |
| epochTime              |   |   |   |   | ✓ |
| ISO8601                |   |   |   |   | ✓ |
| inTimezone             |   |   |   |   | ✓ |
| timezone               |   |   |   |   | ✓ |
| during                 |   |   |   |   | ✓ |
| date                   |   |   |   |   | ✓ |
| timeOfDay              |   |   |   |   | ✓ |
| year                   |   |   |   |   | ✓ |
| month                  |   |   |   |   | ✓ |
| day                    |   |   |   |   | ✓ |
| dayOfWeek              |   |   |   |   | ✓ |
| dayOfYear              |   |   |   |   | ✓ |
| hours                  |   |   |   |   | ✓ |
| minutes                |   |   |   |   | ✓ |
| seconds                |   |   |   |   | ✓ |
| toISO8601              |   |   |   |   | ✓ |
| toEpochTime            |   |   |   |   | ✓ |
| | | | | | |
| **Control structures** 
| array                  |   |   |   |   |   |
| hashMap                |   |   |   |   |   |
| args                   |   |   |   |   |   |
| binary                 |   |   |   |   |   |
| do                     |   |   |   |   |   |
| branch                 |   |   |   |   |   |
| forEach                |   |   |   |   |   |
| range                  |   |   |   |   |   |
| error                  |   |   |   |   |   |
| default                |   |   |   |   |   |
| expr                   |   |   |   |   |   |
| js                     |   |   |   |   |   |
| coerceTo               |   |   |   |   |   |
| typeOf                 |   |   |   |   |   |
| info                   |   |   |   |   |   |
| json                   |   |   |   |   |   |
| "toJsonString, toJSON" |   |   |   |   |   |
| http                   |   |   |   |   |   |
| uuid                   |   |   |   |   |   |
| Geospatial commands    |   |   |   |   |   |
| circle                 |   |   |   |   |   |
| distance               |   |   |   |   |   |
| fill                   |   |   |   |   |   |
| geojson                |   |   |   |   |   |
| toGeojson              |   |   |   |   |   |
| getIntersecting        |   |   |   |   |   |
| getNearest             |   |   |   |   |   |
| includes               |   |   |   |   |   |
| intersects             |   |   |   |   |   |
| line                   |   |   |   |   |   |
| point                  |   |   |   |   |   |
| polygon                |   |   |   |   |   |
| polygonSub             |   |   |   |   |   |
| | | | | | |
| **Administration**
| grant                  |   |   |   |   |   |
| config                 |   |   |   |   |   |
| rebalance              |   |   |   |   |   |
| reconfigure            |   |   |   |   |   |
| status                 |   |   |   |   |   |
| wait                   |   |   |   |   |   |
