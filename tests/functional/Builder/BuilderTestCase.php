<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use functional\Kiboko\Plugin\CSV;
use Kiboko\Component\PHPUnitExtension\BuilderAssertTrait;
use Kiboko\Component\PHPUnitExtension\PipelineAssertTrait;
use PhpParser\Builder as DefaultBuilder;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;

abstract class BuilderTestCase extends TestCase
{
    use BuilderAssertTrait;

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
}
