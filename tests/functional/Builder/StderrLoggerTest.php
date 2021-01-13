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
}
