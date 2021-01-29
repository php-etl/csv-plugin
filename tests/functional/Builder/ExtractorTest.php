<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder\Extractor;
use Kiboko\Plugin\Log\Builder\Logger;
use PhpParser\Node;

final class ExtractorTest extends BuilderTestCase
{
    public function testWithFilePath(): void
    {
        $extract = new Extractor(
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
        $extract = new Extractor(
            new Node\Scalar\String_('tests/functional/files/source-to-extract.csv'),
            new Node\Scalar\String_(';'),
            new Node\Scalar\String_('"'),
            new Node\Scalar\String_('\\'),
        );

        $extract->withLogger(
            (new Logger())->getNode()
        );

        $this->assertBuilderHasLogger(
            '\\Psr\\Log\\NullLogger',
            $extract
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
