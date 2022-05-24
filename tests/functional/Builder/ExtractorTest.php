<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use Kiboko\Plugin\Log;
use PhpParser\Node;

abstract class ExtractorTest extends BuilderTestCase
{
    public function testWithoutOptions(): void
    {
        $extractor = new Builder\Extractor(
            filePath: new Node\Scalar\String_(__DIR__ . '/../files/source-to-extract-comma-delimited.csv'),
        );

        $this->assertBuilderProducesInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extractor
        );

        $this->assertBuilderProducesExtractorIteratesAs(
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ],
            $extractor
        );
    }

    public function testFingersCrossed(): void
    {
        $extractor = new Builder\Extractor(
            filePath: new Node\Scalar\String_(__DIR__ . '/../files/source-to-extract-comma-delimited.csv'),
            safeMode: false,
        );

        $this->assertBuilderProducesInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\FingersCrossed\\Extractor',
            $extractor
        );

        $this->assertBuilderProducesExtractorIteratesAs(
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ],
            $extractor
        );
    }

    public function testWithDelimiter(): void
    {
        $extractor = new Builder\Extractor(
            filePath: new Node\Scalar\String_(__DIR__ . '/../files/source-to-extract-semicolon-delimited.csv'),
            delimiter: new Node\Scalar\String_(';'),
        );

        $this->assertBuilderProducesInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extractor
        );

        $this->assertBuilderProducesExtractorIteratesAs(
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ],
            $extractor
        );
    }

    public function testWithEnclosure(): void
    {
        $extractor = new Builder\Extractor(
            filePath: new Node\Scalar\String_(__DIR__ . '/../files/source-to-extract-comma-delimited-with-enclosure.csv'),
            enclosure: new Node\Scalar\String_('"'),
        );

        $this->assertBuilderProducesInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extractor
        );

        $this->assertBuilderProducesExtractorIteratesAs(
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ],
            $extractor
        );
    }

    public function testWithEscape(): void
    {
        $extractor = new Builder\Extractor(
            filePath: new Node\Scalar\String_(__DIR__ . '/../files/source-to-extract-comma-delimited-with-escape.csv'),
            escape: new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Extractor',
            $extractor
        );

        $this->assertBuilderProducesExtractorIteratesAs(
            [
                ['firstname' => 'pierre \\"louis\\"', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ],
            $extractor
        );
    }
}
