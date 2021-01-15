<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV;

use PhpParser\Builder;
use PhpParser\Node;
use PhpParser\PrettyPrinter;
use PHPUnit\Framework\Constraint\Constraint;
use function sprintf;

final class ExtractorIteratesAs extends Constraint
{
    private array $lines;

    public function __construct(array $lines)
    {
        $this->lines = $lines;
    }

    public function toString(): string
    {
        return sprintf(
            'file iterates %s',
            json_encode($this->lines)
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

        $extractor = include $filename;

        $result = [];
        foreach ($extractor->extract() as $line) {
            $result[] = $line;
        }

        return $result === $this->lines;
    }
}
