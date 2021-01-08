<?php declare(strict_types=1);

namespace functional\Kiboko\Component\ETL\Flow\CSV\Builder;

use Kiboko\Component\ETL\Flow\CSV\Builder;
use PhpParser\Node;

final class ExtractorTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        fopen('vfs://source.csv', 'w');

        $extract = new Builder\Extractor(
            new Node\Scalar\String_('vfs://source.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extract
        );
    }
}
