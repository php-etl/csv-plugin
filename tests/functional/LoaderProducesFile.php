<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV;

use Kiboko\Component\Pipeline\Pipeline;
use Kiboko\Component\Pipeline\PipelineRunner;
use PhpParser\Builder;
use PhpParser\Node;
use PhpParser\PrettyPrinter;
use PHPUnit\Framework\Constraint\Constraint;
use function sprintf;

final class LoaderProducesFile extends Constraint
{
    public function __construct(
        private string $expectedFile,
        private string $actualFile,
        private array $source
    ) {}

    public function toString(): string
    {
        return sprintf(
            'produces file %s',
            $this->expectedFile
        );
    }

    /**
     * @param Builder $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        $printer = new PrettyPrinter\Standard();

        $filename = 'vfs://' . hash('sha512', random_bytes(512)) .'.php';

        file_put_contents($filename, $printer->prettyPrintFile([
            new Node\Stmt\Return_($other->getNode())
        ]));

        $loader = include $filename;

        $pipeline = new Pipeline(
            new PipelineRunner(),
            new \ArrayIterator($this->source)
        );

        $pipeline->load($loader);
        $pipeline->run();

        return file_get_contents($this->expectedFile) === file_get_contents($this->actualFile);
    }
}