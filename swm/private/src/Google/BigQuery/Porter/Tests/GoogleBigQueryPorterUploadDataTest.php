<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use Google\Cloud\BigQuery\Dataset;
use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryStorage;

/**
 * Test C.03 - Insert Data to Table
 * @ATTENTION - DEVELOPER ONLY TEST
 * @ATTENTION - using insertRows (multirows) - available only in paid project
 */
class GoogleBigQueryPorterUploadDataTest extends TestCase
{
    /**
     * @var GoogleBigQueryPorter
     */
    private $porter;
    /**
     * @var Dataset
     */
    private $dataset;
    /**
     * @var GoogleBigQueryStorage
     */
    private $storage;
    /**
     * @var string
     */
    private $datasetId = 'test_dataset';
    /**
     * @var string
     */
    private $tableNameFile = 'projects_by_groups';
    
    public function setUp()
    {
        parent::setUp();
        
        $porterConfig = new GoogleBigQueryPorterConfig(
            17,
            'ozontest-69f09c82892d.json'
        );
        $porterConfig->setDatasetId($this->datasetId);
        
        $this->porter  = new GoogleBigQueryPorter($porterConfig);
        $this->dataset = $this->porter->dataset();
        $this->storage = new GoogleBigQueryStorage($this->tableNameFile);
        $this->porter->table($this->storage);
    }
    
    public function tearDown()
    {
        $this->storage = null;
        $this->dataset = null;
        $this->porter  = null;
        parent::tearDown();
    }
    
    /**
     * @param array $data
     *
     * @dataProvider validDataProvider
     */
    public function testInsertDate(array $data): void
    {
        $response = $this->porter->upload($data);
        static::assertNotEmpty($response);
        static::assertTrue( $response->isSuccessful());
        
        $info = $this->porter->table()->info();
        
        static::assertArrayHasKey('streamingBuffer', $info);
        static::assertArrayHasKey('estimatedRows', $info['streamingBuffer']);
        static::assertArrayHasKey('oldestEntryTime', $info['streamingBuffer']);
        static::assertTrue((int) $info['streamingBuffer']['estimatedRows'] > 0);
        
        // Estimated time for delete
        //echo date('Y-m-d H:i:s', ((int) $info['streamingBuffer']['oldestEntryTime']) / 1000), "\n";
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
                ],
            ],
            [
                [
                    [
                        'group_id'   => 1,
                        'project_id' => 101,
                        'group_name' => 'A:2 - 2x2 ',
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 101,
                        'group_name' => 'A:2 - 2x2 ',
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
                
                ],
            ],
            [
                [
                    [
                        'group_id'   => 1,
                        'project_id' => 101,
                        'group_name' => 'A:3 - 1x3 ',
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 102,
                        'group_name' => 'A:3 - 1x3 ',
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