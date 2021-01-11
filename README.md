CSV Flows
===

Goal
---
This package aims at integrating the CSV reader and writer into the
[Pipeline](https://github.com/php-etl/pipeline) stack.

Principles
---

The tools in this library will produce executable PHP sources, using an intermediate _Abstract Syntax Tree_ from
[nikic/php-parser](https://github.com/nikic/PHP-Parser). This intermediate format helps you combine 
the code produced by this library with other packages from [Middleware](https://github.com/php-etl).

Configuration format
---

### Building an extractor

```yaml
csv:
    extractor:
        file_path: 'path/to/file.csv'
        delimiter: ','
        enclosure: '"'
        escape: '\\'
    logger:
        type: 'stderr'
```

### Building a loader

```yaml
csv:
    loader:
        file_path: 'path/to/file.csv'
        delimiter: ','
        enclosure: '"'
        escape: '\\'
    logger:
        type: 'stderr'
```

Usage
---

This library will build for you either an extractor or a loader that reads or write a CSV.

You can use the following PHP script to test and print the result of your configuration.

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Kiboko\Plugin\CSV;
use PhpParser\Node;
use PhpParser\PrettyPrinter;
use Symfony\Component\Console;
use Symfony\Component\Yaml;

$input = new Console\Input\ArgvInput($argv);
$output = new Console\Output\ConsoleOutput();

class DefaultCommand extends Console\Command\Command
{
    protected static $defaultName = 'test';

    protected function configure()
    {
        $this->addArgument('file', Console\Input\InputArgument::REQUIRED);
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $factory = new CSV\Service();

        $style = new Console\Style\SymfonyStyle(
            $input,
            $output,
        );

        $config = Yaml\Yaml::parse(input: file_get_contents($input->getArgument('file')));

        $style->section('Validation');
        $style->writeln($factory->validate($config) ? '<info>ok</info>' : '<error>failed</error>');
        $style->section('Normalized Config');
        $style->writeln(\json_encode($config = $factory->normalize($config), JSON_PRETTY_PRINT));
        $style->section('Generated code');
        $style->writeln((new PrettyPrinter\Standard())->prettyPrintFile([
            new Node\Stmt\Return_($factory->compile($config)->getNode()),
        ]));

        return 0;
    }
}

(new Console\Application())
    ->add(new DefaultCommand())
    ->run($input, $output)
;
```

See also
---

* [php-etl/pipeline](https://github.com/php-etl/pipeline)
* [php-etl/fast-map](https://github.com/php-etl/fast-map)
* [php-etl/akeneo-expression-language](https://github.com/php-etl/akeneo-expression-language)
