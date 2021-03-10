<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Plugin\CSV\Builder;
use Kiboko\Plugin\Log;
use PhpParser\Node;

final class LoaderTest extends BuilderTestCase
{
    public function testWithoutOptions(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname,lastname
            pierre,dupont
            john,doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }

    public function testFingersCrossed(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname,lastname
            pierre,dupont
            john,doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
            safeMode: false,
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\FingersCrossed\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }

    public function testWithDelimiter(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname;lastname
            pierre;dupont
            john;doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
            delimiter: new Node\Scalar\String_(';'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }

    public function testWithEnclosure(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname,lastname
            "pierre louis",dupont
            john,doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
            enclosure: new Node\Scalar\String_('"'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre louis', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }

    public function testWithEscape(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname,lastname
            "pierre ""louis""",dupont
            john,doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
            enclosure: new Node\Scalar\String_('"'),
            escape: new Node\Scalar\String_('\\'),
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre "louis"', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }

    public function testWithLogger(): void
    {
        file_put_contents('vfs://expected.csv', <<<CSV
            firstname,lastname
            pierre,dupont
            john,doe
            
            CSV);

        $loader = new Builder\Loader(
            filePath: new Node\Scalar\String_('vfs://output.csv'),
        );

        $loader->withLogger(
            (new Log\Builder\Logger())->getNode()
        );

        $this->assertBuilderProducesAnInstanceOf(
            'Kiboko\\Component\\Flow\\Csv\\Safe\\Loader',
            $loader
        );

        $this->assertLoaderProducesFile(
            'vfs://expected.csv',
            'vfs://output.csv',
            $loader,
            [
                ['firstname' => 'pierre', 'lastname' => 'dupont'],
                ['firstname' => 'john', 'lastname' => 'doe']
            ]
        );
    }
}
