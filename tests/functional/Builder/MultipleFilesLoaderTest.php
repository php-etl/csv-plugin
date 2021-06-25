<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use Kiboko\Component\PHPUnitExtension\PipelineAssertTrait;
use Kiboko\Plugin\CSV\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\Expression;
use Vfs\FileSystem;
use function Kiboko\Component\SatelliteToolbox\Configuration\compileExpression;

final class MultipleFilesLoaderTest extends BuilderTestCase
{
    use PipelineAssertTrait;

    private ?FileSystem $fs = null;

    protected function setUp(): void
    {
        $this->fs = FileSystem::factory('vfs://');
        $this->fs->mount();
    }

    protected function tearDown(): void
    {
        $this->fs->unmount();
        $this->fs = null;
    }

    public function testWithoutOptions(): void
    {
        file_put_contents('vfs://expected-1.csv', <<<CSV
            firstname,lastname
            Pierre,Dupont
            John,Doe
            Frank,O'hara
            
            CSV);

        file_put_contents('vfs://expected-2.csv', <<<CSV
            firstname,lastname
            Hiroko,Froncillo
            Marlon,Botz
            Billy,Hess
            
            CSV);

        $loader = new Builder\MultipleFilesLoader(
            filePath: compileExpression(new \functional\Kiboko\Plugin\CSV\ExpressionLanguage\ExpressionLanguage(), new Expression('format("vfs://SKU_%06d.csv", index)'), 'index'),
            maxLines: new Node\Scalar\LNumber(3)
        );

        $this->assertBuilderProducesPipelineLoadingLike(
            [
                [
                    'firstname' => 'Pierre',
                    'lastname' => 'Dupont',
                ],
                [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                ],
                [
                    'firstname' => 'Frank',
                    'lastname' => 'O\'hara',
                ],
                [
                    'firstname' => 'Hiroko',
                    'lastname' => 'Froncillo',
                ],
                [
                    'firstname' => 'Marlon',
                    'lastname' => 'Botz',
                ],
                [
                    'firstname' => 'Billy',
                    'lastname' => 'Hess',
                ],
                [
                    'firstname' => 'Henry',
                    'lastname' => 'Sellers',
                ],
            ],
            [
                [
                    'firstname' => 'Pierre',
                    'lastname' => 'Dupont',
                ],
                [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                ],
                [
                    'firstname' => 'Frank',
                    'lastname' => 'O\'hara',
                ],
                [
                    'firstname' => 'Hiroko',
                    'lastname' => 'Froncillo',
                ],
                [
                    'firstname' => 'Marlon',
                    'lastname' => 'Botz',
                ],
                [
                    'firstname' => 'Billy',
                    'lastname' => 'Hess',
                ],
                [
                    'firstname' => 'Henry',
                    'lastname' => 'Sellers',
                ],
            ],
            $loader,
        );

        $this->assertFileEquals('vfs://expected-1.csv', 'vfs://SKU_000000.csv');
        $this->assertFileEquals('vfs://expected-2.csv', 'vfs://SKU_000001.csv');
    }
}
