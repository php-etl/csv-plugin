<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory;

use Kiboko\Plugin\CSV;
use Kiboko\Contract\Configurator;
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
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }

    public function compile(array $config): Repository\Loader
    {
        $loader = new CSV\Builder\Loader(
            filePath: new Node\Scalar\String_($config['file_path']),
            delimiter: array_key_exists('delimiter', $config) ? new Node\Scalar\String_($config['delimiter']) : null,
            enclosure: array_key_exists('enclosure', $config) ? new Node\Scalar\String_($config['enclosure']) : null,
            escape: array_key_exists('escape', $config) ? new Node\Scalar\String_($config['escape']) : null,
            columns: array_key_exists('columns', $config) ? $this->toAst($config['columns']) : null,
        );

        if (array_key_exists('safe_mode', $config)) {
            if ($config['safe_mode'] === true) {
                $loader->withSafeMode();
            } else {
                $loader->withFingersCrossedMode();
            }
        }

        return new Repository\Loader($loader);
    }

    private function toAst($value): Node\Expr
    {
        if (is_string($value)) {
            return new Node\Scalar\String_($value);
        }
        if (is_float($value)) {
            return new Node\Scalar\DNumber($value);
        }
        if (is_int($value)) {
            return new Node\Scalar\LNumber($value);
        }
        if (is_array($value)) {
            $items = [];

            foreach ($value as $key => $item) {
                $items[] = new Node\Expr\ArrayItem(
                    value: $this->toAst($item),
                );
            }

            return new Node\Expr\Array_(attributes: [Node\Expr\Array_::KIND_SHORT]);
        }

        throw new \InvalidArgumentException(strtr(
            'Unsupported type %actual%.',
            [
                '%actual%' => get_debug_type($value),
            ]
        ));
    }
}
