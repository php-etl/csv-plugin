<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Service;

use Kiboko\Contract\ETL\Configurator\InvalidConfigurationException;
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
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\',
                ],
                'logger' => [
                    'type' => 'stderr'
                ]
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Builder\\Extractor',
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
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\',
                ],
                'logger' => [
                    'type' => 'stderr'
                ]
            ],
            'expected_class' => 'Kiboko\\Plugin\\CSV\\Builder\\Loader',
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
        $config = $service->normalize($actual);
        $builder = $service->compile($config);

        $this->assertTrue(
            $service->validate($actual)
        );

        $this->assertEquals(
            new CSV\Configuration(),
            $service->configuration()
        );

        $this->assertEquals(
            $expected,
            $config
        );

        $this->assertInstanceOf(
            $expectedClass,
            $builder
        );
    }

    public function testWrongConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
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

        $this->assertFalse($service->validate($wrongConfig));
        $this->assertEquals(
            false,
            $service->validate($wrongConfig)
        );
    }

    public function testWrongWithBothExtractAndLoad(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Could not determine if the factory should build an extractor or a loader.');

        $service = new CSV\Service();
        $service->compile([
            'csv' => []
        ]);
    }
}