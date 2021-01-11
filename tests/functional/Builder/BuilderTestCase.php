<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use functional\Kiboko\Plugin\CSV\BuilderProducesAnInstanceOf;
use PhpParser\Builder as DefaultBuilder;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;

abstract class BuilderTestCase extends TestCase
{
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

    protected function assertBuilderProducesAnInstanceOf(string $expected, DefaultBuilder $builder, string $message = '')
    {
        static::assertThat(
            $builder,
            new BuilderProducesAnInstanceOf($expected),
            $message
        );
    }

    protected function assertBuilderNotProducesAnInstanceOf(string $expected, DefaultBuilder $builder, string $message = '')
    {
        static::assertThat(
            $builder,
            new LogicalNot(
                new BuilderProducesAnInstanceOf($expected),
            ),
            $message
        );
    }
}
