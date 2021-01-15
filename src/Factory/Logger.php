<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory;

use Kiboko\Plugin\CSV;
use Kiboko\Contract\ETL\Configurator;
use PhpParser\Node;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Logger implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new CSV\Configuration\Logger();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws Configurator\ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        return $this->processor->processConfiguration($this->configuration, $config);
    }

    public function validate(array $config): bool
    {
        if ($this->normalize($config)) {
            return true;
        }

        return false;
    }

    public function compile(array $config): CSV\Builder\Logger
    {
        $builder = new CSV\Builder\Logger();

        if (isset($config['type']) && $config['type'] === 'stderr') {
            $builder->withLogger((new CSV\Builder\StderrLogger())->getNode());
        } else {
            $builder->withLogger((new CSV\Builder\NullLogger())->getNode());
        }

        return $builder;
    }
}
