<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use PhpParser\Node;
use PhpParser\PrettyPrinter;

final class StderrLoggerTest extends BuilderTestCase
{
    public function testStderrLogger(): void
    {
        $log = new Builder\StderrLogger();

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\AbstractLogger',
            $log
        );
    }

    public function testBuilderExactResult(): void
    {
        $expected = <<<EOD
<?php

return new class extends \Psr\Log\AbstractLogger
{
    public function log(\$level, \$message, array \$context = [])
    {
        \\file_put_contents('php://stderr', \sprintf('[%s] %s', \$level, \$message) . \PHP_EOL);
    }
};
EOD;

        $log = new Builder\StderrLogger();
        $printer = new PrettyPrinter\Standard();

        $actual = $printer->prettyPrintFile([
            new Node\Stmt\Return_($log->getNode()),
        ]);

        $this->assertEquals(
            $expected,
            $actual
        );
    }
}
