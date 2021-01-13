<?php declare(strict_types=1);

namespace functional\Factory;

use Kiboko\Contract\ETL\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\CSV;
use PHPUnit\Framework\TestCase;

final class ExtractorTest extends TestCase
{
    public function configProvider()
    {
        yield [
            'expected' => [
                'file_path' => 'input.csv',
                'delimiter' => ',',
                'enclosure' => '"',
                'escape' => '\\',
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'input.csv'
                ]
            ]
        ];
    }

    /**
     * @dataProvider configProvider
     */
    public function testWithConfiguration(array $expected, array $actual): void
    {
        $factory = new CSV\Factory\Extractor();
        $normalizedConfig = $factory->normalize($actual);

        $this->assertEquals(
            new CSV\Configuration\Extractor(),
            $factory->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue($factory->validate($actual));
    }

    public function testWrongConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "file_path" under "extractor" must be configured.');

        $wrongConfig = [
            'extractor' => []
        ];

        $factory = new CSV\Factory\Extractor();
        $factory->normalize($wrongConfig);
    }
}
