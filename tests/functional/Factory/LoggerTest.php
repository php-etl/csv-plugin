<?php declare(strict_types=1);

namespace functional\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\CSV;
use PHPUnit\Framework\TestCase;

final class LoggerTest extends TestCase
{
    public function configProvider()
    {
        yield [
            'expected' => [
                'type' => 'stderr'
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Builder\\Logger',
            'actual' => [
                'logger' => [
                    'type' => 'stderr'
                ]
            ]
        ];

        yield [
            'expected' => [
                'type' => 'null'
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Builder\\Logger',
            'actual' => [
                'logger' => [
                    'type' => 'null'
                ]
            ]
        ];
    }

    /**
     * @dataProvider configProvider
     */
    public function testWithConfiguration(array $expected, string $expectedClass, array $actual): void
    {
        $factory = new CSV\Factory\Logger();
        $normalizedConfig = $factory->normalize($actual);

        $this->assertEquals(
            new CSV\Configuration\Logger(),
            $factory->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue(
            $factory->validate($actual)
        );

        $this->assertInstanceOf(
            $expectedClass,
            $factory->compile($normalizedConfig)
        );
    }

    public function testFailToValidate(): void
    {
        $factory = new CSV\Factory\Logger();
        $this->assertFalse($factory->validate([]));
    }
}
