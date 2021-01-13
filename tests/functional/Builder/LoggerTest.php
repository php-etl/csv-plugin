<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;

final class LoggerTest extends BuilderTestCase
{
    public function testWithStderrLogger(): void
    {
        $log = new Builder\Logger(
            (new Builder\StderrLogger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\AbstractLogger',
            $log
        );
    }

    public function testWithoutSpecifiedLogger(): void
    {
        $log = new Builder\Logger();

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\AbstractLogger',
            $log
        );
    }
}
