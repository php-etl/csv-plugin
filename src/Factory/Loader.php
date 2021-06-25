<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory;

use Kiboko\Plugin\CSV;
use Kiboko\Contract\Configurator;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use function Kiboko\Component\SatelliteToolbox\Configuration\compileValueWhenExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\compileValue;

final class Loader implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct(private ExpressionLanguage $interpreter)
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
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }

    public function compile(array $config): Repository\Loader
    {
        if (array_key_exists('max_lines', $config)) {
            $loader = new CSV\Builder\MultipleFilesLoader(
                filePath: compileValueWhenExpression($this->interpreter, $config['file_path'], 'index'),
                maxLines: compileValueWhenExpression($this->interpreter, $config['max_lines']),
                delimiter: array_key_exists('delimiter', $config) ? compileValueWhenExpression($this->interpreter, $config['delimiter']) : null,
                enclosure: array_key_exists('enclosure', $config) ? compileValueWhenExpression($this->interpreter, $config['enclosure']) : null,
                escape: array_key_exists('escape', $config) ? compileValueWhenExpression($this->interpreter, $config['escape']) : null,
                columns: array_key_exists('columns', $config) ? compileValue($this->interpreter, $config['columns']) : null
            );
        } else {
            $loader = new CSV\Builder\Loader(
                filePath: compileValueWhenExpression($this->interpreter, $config['file_path']),
                delimiter: array_key_exists('delimiter', $config) ? compileValueWhenExpression($this->interpreter, $config['delimiter']) : null,
                enclosure: array_key_exists('enclosure', $config) ? compileValueWhenExpression($this->interpreter, $config['enclosure']) : null,
                escape: array_key_exists('escape', $config) ? compileValueWhenExpression($this->interpreter, $config['escape']) : null,
                columns: array_key_exists('columns', $config) ? compileValue($this->interpreter, $config['columns']) : null,
            );
        }

        if (array_key_exists('safe_mode', $config)) {
            if ($config['safe_mode'] === true) {
                $loader->withSafeMode();
            } else {
                $loader->withFingersCrossedMode();
            }
        }

        return new Repository\Loader($loader);
    }
}
