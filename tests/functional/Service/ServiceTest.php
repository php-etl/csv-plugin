<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Service;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\CSV;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function configProvider()
    {
        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'input.csv',
                ],
                'logger' => [
                    'type' => 'stderr'
                ]
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Factory\\Repository\\Extractor',
            'actual' => [
                'csv' => [
                    'extractor' => [
                        'file_path' => 'input.csv'
                    ],
                    'logger' => [
                        'type' => 'stderr'
                    ]
                ]
            ]
        ];

        yield [
            'expected' => [
                'loader' => [
                    'file_path' => 'output.csv',
                ],
                'logger' => [
                    'type' => 'stderr'
                ]
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Factory\\Repository\\Loader',
            'actual' => [
                'csv' => [
                    'loader' => [
                        'file_path' => 'output.csv'
                    ],
                    'logger' => [
                        'type' => 'stderr'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider configProvider
     */
    public function testWithConfigurationAndProcessor(array $expected, string $expectedClass, array $actual): void
    {
        $service = new CSV\Service();
        $normalizedConfig = $service->normalize($actual);

        $this->assertEquals(
            new CSV\Configuration(),
            $service->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue($service->validate($actual));
        $this->assertFalse($service->validate(['logger' => []]));

        $this->assertInstanceOf(
            $expectedClass,
            $service->compile($normalizedConfig)
        );
    }

    public function testWithBothExtractAndLoad(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your configuration should either contain the "extractor" or the "loader" key, not both.');

        $wrongConfig = [
            'csv' => [
                'extractor' => [
                    'file_path' => 'input.csv'
                ],
                'loader' => [
                    'file_path' => 'output.csv'
                ]
            ]
        ];

        $service = new CSV\Service();
        $service->normalize($wrongConfig);
    }

    public function testWrongConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Could not determine if the factory should build an extractor or a loader.');

        $service = new CSV\Service();
        $service->compile([
            'csv' => []
        ]);
    }
}
