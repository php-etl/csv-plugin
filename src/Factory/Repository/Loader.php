<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\CSV;

final class Loader implements Configurator\RepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private CSV\Builder\Loader $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): CSV\Builder\Loader
    {
        return $this->builder;
    }
}
