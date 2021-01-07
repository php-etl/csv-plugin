<?php declare(strict_types=1);

namespace functional\Kiboko\Component\ETL\Flow\CSV\Builder;

use Kiboko\Component\ETL\Flow\CSV\Builder;
use Kiboko\Component\Flow\Csv\Safe\Extractor;

final class ExtractTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $extract = new Builder\Extractor();
        $this->assertNodeIsInstanceOf(Extractor::class, $extract);
    }
}
