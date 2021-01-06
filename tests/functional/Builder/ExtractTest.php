<?php declare(strict_types=1);

namespace functional\Kiboko\Component\ETL\Flow\CSV\Builder;

use Kiboko\Component\ETL\Flow\CSV\Builder;

class ExtractTest extends BuilderTestCase
{
    public function testWithFilePath()
    {
        $extract = new Builder\Extractor();

        $this->assertNodeIsInstanceOf('', $extract);
    }
}
