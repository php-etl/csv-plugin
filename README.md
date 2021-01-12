# CSV Plugin
This package aims at integrating the CSV reader and writer into the
[Pipeline](https://github.com/php-etl/pipeline) stack.

## Principles
The tools in this library will produce executable PHP sources, using an intermediate _Abstract Syntax Tree_ from
[nikic/php-parser](https://github.com/nikic/PHP-Parser). This intermediate format helps you combine 
the code produced by this library with other packages from [Middleware](https://github.com/php-etl).

## Installation
```
composer require php-etl/csv-plugin
```

# Usage
Example of a config file. Reads `input.csv`, writes `output.csv`, logs error.
```yaml
csv:
    extractor:
        file_path: input.csv
        delimiter: ';'
        enclosure: '"'
        escape: '\\'
    loader:
        file_path: output.csv
        delimiter: ','
        enclosure: '"'
        escape: '\\'
    logger:
        type: stderr
```
## [Examples of configuration files](docs/examples.md)

## See also
* [php-etl/pipeline](https://github.com/php-etl/pipeline)
* [php-etl/fast-map](https://github.com/php-etl/fast-map)
* [php-etl/akeneo-expression-language](https://github.com/php-etl/akeneo-expression-language)
