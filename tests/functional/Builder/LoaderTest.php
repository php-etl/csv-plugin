<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use Kiboko\Plugin\Log;
use PhpParser\Node;

final class LoaderTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $load = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://destination.csv'),
            delimiter: new Node\Scalar\String_(';'),
//            enclosure: new Node\Scalar\String_('"'),
//            escape: new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $load
        );

        $this->assertLoaderProducesFile(
            'tests/functional/files/expected-to-load.csv',
            'vfs://destination.csv',
            $load,
            [
                ['prenom' => 'pierre', 'nom de famille' => 'dupont'],
                ['prenom' => 'john', 'nom de famille' => 'doe']
            ]
        );
    }

    public function testWithFilePathAndLogger(): void
    {
        $load = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://destination.csv'),
            delimiter: new Node\Scalar\String_(';'),
//            enclosure: new Node\Scalar\String_('"'),
//            escape: new Node\Scalar\String_('\\'),
        );

        $load->withLogger(
            (new Log\Builder\Logger())->getNode()
        );

        $this->assertBuilderHasLogger(
            '\\Psr\\Log\\NullLogger',
            $load
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $load
        );

        $this->assertLoaderProducesFile(
            'tests/functional/files/expected-to-load.csv',
            'vfs://destination.csv',
            $load,
            [
                ['prenom' => 'pierre', 'nom de famille' => 'dupont'],
                ['prenom' => 'john', 'nom de famille' => 'doe']
            ]
        );
    }
}
