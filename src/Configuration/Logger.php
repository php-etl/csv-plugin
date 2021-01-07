<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Flow\CSV\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Logger implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('logger');

        $builder->getRootNode()
            ->children()
                ->enumNode('type')->values(['null', 'stderr'])->end()
            ->end();
        return $builder;
    }
}
