<?php declare(strict_types=1);

namespace Kiboko\Component\ETL\Flow\CSV\Configuration;

use Symfony\Component\Config;

final class Loader implements Config\Definition\ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $builder = new Config\Definition\Builder\TreeBuilder('loader');

        $builder->getRootNode()
            ->children()
                ->scalarNode('file_path')->isRequired()->end()
                ->scalarNode('delimiter')->defaultValue(',')->end()
                ->scalarNode('enclosure')->defaultValue('"')->end()
                ->scalarNode('escape')->defaultValue('\\')->end()
            ->end();

        return $builder;
    }
}
