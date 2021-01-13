<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;

final class NullLoggerTest extends BuilderTestCase
{
    public function testNullLogger(): void
    {
        $log = new Builder\NullLogger();

        $this->assertBuilderProducesAnInstanceOf(
            'Psr\\Log\\NullLogger',
            $log
        );
    }
}
