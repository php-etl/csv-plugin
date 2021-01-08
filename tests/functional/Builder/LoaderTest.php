<?php declare(strict_types=1);

namespace functional\Kiboko\Component\ETL\Flow\CSV\Builder;

use Kiboko\Component\ETL\Flow\CSV\Builder;
use PhpParser\Node;

final class LoaderTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $extract = new Builder\Loader(
            new Node\Scalar\String_('path.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\ETL\\Flow\\SPL\\CSV\\Safe\\Loader',
            $extract
        );
    }
}
