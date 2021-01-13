<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Service;

use Kiboko\Plugin\CSV\Service;
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
    }

    /**
     * @dataProvider configProvider
     */
    public function testWithConfigurationAndProcessor($expected, $actual): void
    {
        $service = new Service();

        $this->assertTrue(
            $service->validate($actual)
        );

        $this->assertEquals(
            $expected,
            $service->normalize($actual)
        );
    }
}
