<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Logger implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('logger');

        $builder->getRootNode()
            ->children()
                ->enumNode('type')->values(['null', 'stderr'])->end()
            ->end();
        return $builder;
    }
}
