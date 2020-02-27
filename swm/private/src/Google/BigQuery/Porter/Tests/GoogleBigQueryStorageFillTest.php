<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryStorage;

/**
 * Tet A.02 - Fill Storage and Process Data
 */
class GoogleBigQueryStorageFillTest extends TestCase
{
    /**
     * @var string
     */
    private $tableNameFile = 'projects_by_groups';
    /**
     * @var GoogleBigQueryStorage
     */
    private $storage;
    
    public function setUp()
    {
        parent::setUp();
        $this->storage = new GoogleBigQueryStorage($this->tableNameFile);
    }
    
    public function tearDown()
    {
        $this->storage = null;
        parent::tearDown();
    }
    
    /**
     * @param array $data
     *
     * @dataProvider validDataProvider
     */
    public function testProcessedDate(array $data): void
    {
        $this->storage->fill($data);
        $rows = $this->storage->rows();
        static::assertNotEmpty($rows);
        static::assertCount(6, $rows);
        static::assertCount(4, $rows[0]['data']);
        static::assertEquals('A:1 - 3x1 ', $rows[0]['data']['group_name']);
        static::assertEquals(1, $rows[0]['data']['group_id']);
        static::assertEquals(gmdate('Y-m-d H:i:s'), $rows[0]['data']['created_at']);
    }
    
    public function validDataProvider(): array
    {
        return [
            [
                [
                    [
                        'group_id'   => 1,
                        'project_id' => 101,
                        'group_name' => 'A:1 - 3x1 ',
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 101,
                        'group_name' => 'A:1 - 3x1 ',
                    ],
                    [
                        'group_id'   => 3,
                        'project_id' => 101,
                        'group_name' => 'A:1 - 3x1 ',
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 102,
                        'group_name' => 'A:2 - 2x2 ',
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 102,
                        'group_name' => 'A:2 - 2x2 ',
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 103,
                        'group_name' => 'A:3 - 1x3 ',
                    ],
                ],
            ],
        ];
    }
}