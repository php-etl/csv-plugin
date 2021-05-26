<?php declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\CSV;
use Kiboko\Contract\Configurator;
use PhpParser\Node;
use PhpParser\ParserFactory;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class Extractor implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    //private ?ExpressionLanguage $interpreter = null
    public function __construct(private ExpressionLanguage $interpreter)
    {
        $this->processor = new Processor();
        $this->configuration = new CSV\Configuration\Extractor();
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

    private function compileValue(string|Expression $value): Node\Expr
    {
        if (is_string($value)) {
            return new Node\Scalar\String_(value: $value);
        }
        if ($value instanceof Expression) {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7, null);
            return $parser->parse('<?php ' . $this->interpreter->compile($value, ['input']) . ';')[0]->expr;
        }

        throw new InvalidConfigurationException(
            message: 'Could not determine the correct way to compile the provided filter.',
        );
    }

    public function compile(array $config): Repository\Extractor
    {
        $extractor = new CSV\Builder\Extractor(
            filePath: $this->compileValue($config['file_path']),
            delimiter: array_key_exists('delimiter', $config) ? $this->compileValue($config['delimiter']) : null,
            enclosure: array_key_exists('enclosure', $config) ? $this->compileValue($config['enclosure']) : null,
            escape: array_key_exists('escape', $config) ? $this->compileValue($config['escape']) : null,
            columns: array_key_exists('columns', $config) ? $this->toAst($config['columns']) : null
        );

        if (array_key_exists('safe_mode', $config)) {
            if ($config['safe_mode'] === true) {
                $extractor->withSafeMode();
            } else {
                $extractor->withFingersCrossedMode();
            }
        }

        return new Repository\Extractor($extractor);
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
