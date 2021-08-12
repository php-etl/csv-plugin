<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV;

use Kiboko\Component\Satellite\NamedConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface, NamedConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $extractor = new Configuration\Extractor();
        $loader = new Configuration\Loader();

        $builder = new TreeBuilder($this->getName());
        $builder->getRootNode()
            ->validate()
                ->ifTrue(function (array $value) {
                    return array_key_exists('extractor', $value) && array_key_exists('loader', $value);
                })
                ->thenInvalid('Your configuration should either contain the "extractor" or the "loader" key, not both.')
            ->end()
            ->children()
                ->arrayNode('expression_language')
                    ->scalarPrototype()->end()
                ->end()
                ->append(node: $extractor->getConfigTreeBuilder()->getRootNode())
                ->append(node: $loader->getConfigTreeBuilder()->getRootNode())
                ->variableNode('logger')
                    ->setDeprecated('php-etl/csv-plugin', '0.2')
                ->end()
            ->end()
        ;

        return $builder;
    }

    public function getName(): string
    {
        return 'csv';
    }
}
