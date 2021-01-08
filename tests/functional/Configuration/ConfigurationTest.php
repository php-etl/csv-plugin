<?php declare(strict_types=1);

namespace functional\Kiboko\Component\ETL\Flow\CSV\Configuration;

use Kiboko\Component\ETL\Flow\CSV\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

class ConfigurationTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public function validConfigProvider()
    {
        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ';',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ';',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                ]
            ]
        ];

        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'logger' => [
                    'type' => 'null'
                ],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'logger' => [
                    'type' => 'null'
                ],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ]
        ];
    }

    /**
     * @dataProvider validConfigProvider
     */
    public function testValidConfig($expected, $actual)
    {
        $config = new Configuration();

        $this->assertEquals(
            $expected,
            $this->processor->processConfiguration(
                $config,
                [
                    $actual
                ]
            )
        );
    }

    public function testMissingFilePath()
    {
        $this->expectException(Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "file_path" under "csv.extractor" must be configured.');

        $config = new Configuration();
        $this->processor->processConfiguration(
            $config,
            [
                [
                    'extractor' => [
                        'enclosure' => '"',
                    ]
                ]
            ]
        );
    }

    public function testMissingOptionsInLoader()
    {
        $this->expectException(Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "file_path" under "csv.loader" must be configured.');

        $config = new Configuration();
        $this->processor->processConfiguration(
            $config,
            [
                [
                    'loader' => [
                    ]
                ]
            ]
        );
    }
}
