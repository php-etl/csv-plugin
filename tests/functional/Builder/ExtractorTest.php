<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use PhpParser\Node;

final class ExtractorTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $extract = new Builder\Extractor(
            new Node\Scalar\String_('tests/functional/files/source-to-extract.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extract
        );

        $this->assertExtractorIteratesAs(
            [
                ['name' => 'pierre', 'last name' => 'dupont'],
                ['name' => 'john', 'last name' => 'doe']
            ],
            $extract
        );
    }

    public function testWithFilePathAndLogger(): void
    {
        $extract = new Builder\Extractor(
            new Node\Scalar\String_('tests/functional/files/source-to-extract.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $extract->withLogger(
            (new Builder\Logger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extract
        );

        $this->assertExtractorIteratesAs(
            [
                ['name' => 'pierre', 'last name' => 'dupont'],
                ['name' => 'john', 'last name' => 'doe']
            ],
            $extract
        );
    }
}
