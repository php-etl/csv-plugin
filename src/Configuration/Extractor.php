<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Extractor implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('extractor');

        $builder->getRootNode()
            ->children()
                ->scalarNode('file_path')->isRequired()->end()
                ->scalarNode('delimiter')->end()
                ->scalarNode('enclosure')->end()
                ->scalarNode('escape')->end()
                ->booleanNode('safe_mode')->end()
                ->variableNode('columns')
                    ->validate()
                        ->ifTrue(fn ($value) => $value !== null || !is_array($value))
                        ->thenInvalid('Value should be an array')
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
