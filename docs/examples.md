# Structure of the configuration file
```yaml
csv:
    extractor:
        file_path:  # Path to the source file
        
        delimiter:  # Optionnal. Character used by the file to delimit columns.
                    # Default value: ,
        
        enclosure:  # Optionnal. Character used to enclose values.
                    # Default value: "
        
        escape:     # Optionnal. Character used to escape special characters (quotes, slashes).
                    # Default value: \\
        
    loader:
        file_path:  # Path to the destination file

        delimiter:  # Optionnal. Character used by the file to delimit columns.
                    # Default value: ,

        enclosure:  # Optionnal. Character used to enclose values.
                    # Default value: "

        escape:     # Optionnal. Character used to escape special characters (quotes, slashes).
                    # Default value: \\
    logger:
        type:       # Possible values: stderr, null
```

## Reading from a CSV file
```yaml
csv:
    extractor:
        file_path: path/of/my/input.csv
        delimiter: ';'
        enclosure: '"'
        escape: '\\'
```

## Writing to a CSV file
```yaml
csv:
    loader:
        file_path: path/to/my/output.csv
        delimiter: ';'
        enclosure: '"'
        escape: '\\'
```

## Outputing logs
`logger:` writes logs and errors to your system's [`stderr`](https://en.wikipedia.org/wiki/Standard_streams#Standard_error_(stderr)).
Other value available: `null`
```yaml
csv:
    logger:
        type: stderr
```

## Read a CSV file, write the result to an other CSV file
We read the file `my-input.csv`. It uses commas (`,`) to separate columns.

We then write it to `my-output.csv`, with semicolons (`;`) as the delimiter. We do not provide the other options.

Any error will be stored in [stderr](https://en.wikipedia.org/wiki/Standard_streams#Standard_error_(stderr)).

settings.yml:
```yaml
csv:
    extractor:
        file_path: my-input.csv
        delimiter: ','
        enclosure: '"'
        escape: '\\'
    loader:
        file_path: my-output.csv
        delimiter: ';'
    logger:
        type: stderr
```

my-input.csv:
```
"John","Doe","Miami"
"Jean","Dupont","Paris"
```

my-output.csv:
```
"John";"Doe";"Miami"
"Jean";"Dupont";"Paris"
```
