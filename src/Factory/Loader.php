<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory;

use Kiboko\Plugin\CSV;
use Kiboko\Contract\ETL\Configurator;
use PhpParser\Node;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Loader implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new CSV\Configuration\Loader();
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
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        if ($this->normalize($config)) {
            return true;
        }

        return false;
    }

    public function compile(array $config): CSV\Builder\Loader
    {
        return new CSV\Builder\Loader(
            new Node\Scalar\String_($config['file_path']),
            new Node\Scalar\String_($config['delimiter']),
            new Node\Scalar\String_($config['enclosure']),
            new Node\Scalar\String_($config['escape']),
        );
    }
}
