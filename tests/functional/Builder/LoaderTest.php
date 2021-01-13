<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use PhpParser\Node;

final class LoaderTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $load = new Builder\Loader(
            new Node\Scalar\String_('vfs://destination.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $load
        );
    }

    public function testWithFilePathAndLogger(): void
    {
        $load = new Builder\Loader(
            new Node\Scalar\String_('vfs://destination.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $load->withLogger(
            (new Builder\Logger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $load
        );
    }
}
