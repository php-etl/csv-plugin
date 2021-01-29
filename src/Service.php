<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV;

use Kiboko\Contract\Configurator\FactoryInterface;
use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Contract\Configurator\RepositoryInterface;
use Kiboko\Plugin\CSV\Factory;
use Kiboko\Plugin\Log\Service as LoggerFactory;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Service implements FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        if ($this->processor->processConfiguration($this->configuration, $config)) {
            return true;
        }

        return false;
    }

    public function compile(array $config): RepositoryInterface
    {
        $loggerFactory = new LoggerFactory();

        try {
            if (array_key_exists('extractor', $config)) {
                $extractorFactory = new Factory\Extractor();

                $extractor = $extractorFactory->compile($config['extractor']);
                $logger = $loggerFactory->compile($config['logger'] ?? []);

                $extractor->getBuilder()->withLogger($logger->getBuilder()->getNode());

                return $extractor;
            } elseif (array_key_exists('loader', $config)) {
                $loaderFactory = new Factory\Loader();

                $loader = $loaderFactory->compile($config['loader']);
                $logger = $loggerFactory->compile($config['logger'] ?? []);

                $loader->getBuilder()->withLogger($logger->getBuilder()->getNode());

                return $loader;
            } else {
                throw new InvalidConfigurationException(
                    'Could not determine if the factory should build an extractor or a loader.'
                );
            }
        } catch (InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }
}
