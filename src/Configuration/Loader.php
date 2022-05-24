<?php

declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Configuration;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Loader implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('file_path')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->integerNode('max_lines')
                    ->min(1)
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('delimiter')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('enclosure')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('escape')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->booleanNode('safe_mode')->end()
                ->variableNode('columns')
                    ->validate()
                        ->ifTrue(fn ($value) => null !== $value && !\is_array($value))
                        ->thenInvalid('Value should be an array')
                    ->end()
                    ->validate()
                        ->ifTrue(fn ($value) => null === $value)
                        ->thenInvalid('Value cannot be null')
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
