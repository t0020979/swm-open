<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use Google\Cloud\BigQuery\Dataset;
use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryTableConfig;
use Seowork\Helper\DateTimeHelper;

/**
 * Test 013 - Insert Data to Table
 * @ATTENTION - DEVELOPER ONLY TEST
 * @package   Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryTableInsertTest extends TestCase
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
     * @var string
     */
    private $datasetName = 'test_dataset';
    /**
     * @var string
     */
    private $tableSchemaName = 'groups_by_projects';
    /**
     * @var GoogleBigQueryTableConfig
     */
    private $tableConfig;
    
    public function setUp()
    {
        parent::setUp();
        
        //// sw-test
        //$porterConfig =  GoogleBigQueryPorterConfig::overKeyFilePath(
        //    'sw-test-20-e97d669c29ba.json',
        //    '/app/private/src/Google/BigQuery/Porter/Tests/'
        //);
        
        $porterConfig = GoogleBigQueryPorterConfig::overKeyFilePath(
            'ozontest-69f09c82892d.json'
        );
        
        $this->porter  = GoogleBigQueryPorter::build($porterConfig);
        $this->dataset = $this->porter->dataset($this->datasetName);
        
        $this->tableConfig = GoogleBigQueryTableConfig::overJsonFile($this->tableSchemaName);
        $this->porter->setTable($this->tableConfig);
    }
    
    public function tearDown()
    {
        //if ($this->porter->table()->exists()) {
        //    $this->porter->table()->delete();
        //}
        
        $this->porter      = null;
        $this->dataset     = null;
        $this->tableConfig = null;
        parent::tearDown();
    }
    
    public function notestInsertData1(): void
    {
        //static::assertEquals($this->dataset->exists(), true);
        
        //$info = $this->dataset->info();
        //static::assertEquals($this->datasetName, $info['datasetReference']['datasetId']);
        
        var_dump($this->dataset->info()['id']);
        
        //$table = $this->porter->table();
        //static::assertEquals(true, $table->exists());
        
        //$this->porter->setTable($this->tableConfig);
        
        var_dump($this->porter->table()->info()['id']);
    }
    
    /**
     * @param array $data
     *
     * @dataProvider validBigDataProvider
     */
    public function testInsertDate(array $data): void
    {
        $table = $this->porter->table();
        
        $rows = [];
        foreach ($data as $row) {
            $rows[] = ['data' => $row];
        }
        
        $response = $table->insertRows($rows);
        //var_dump($response);
        
        static::assertNotEmpty($response);
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
                        'created_at' => gmdate('Y-m-d H:i:01'),
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 101,
                        'group_name' => 'A:1 - 3x1 ',
                        'created_at' => gmdate('Y-m-d H:i:01'),
                    ],
                    [
                        'group_id'   => 3,
                        'project_id' => 101,
                        'group_name' => 'A:1 - 3x1 ',
                        'created_at' => gmdate('Y-m-d H:i:01'),
                    ],
                ],
            ],
            [
                [
                    [
                        'group_id'   => 1,
                        'project_id' => 101,
                        'group_name' => 'A:2 - 2x2 ',
                        'created_at' => gmdate('Y-m-d H:i:02'),
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 101,
                        'group_name' => 'A:2 - 2x2 ',
                        'created_at' => gmdate('Y-m-d H:i:02'),
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 102,
                        'group_name' => 'A:2 - 2x2 ',
                        'created_at' => gmdate('Y-m-d H:i:02'),
                    ],
                    [
                        'group_id'   => 2,
                        'project_id' => 102,
                        'group_name' => 'A:2 - 2x2 ',
                        'created_at' => gmdate('Y-m-d H:i:02'),
                    ],
                
                ],
            ],
            [
                [
                    [
                        'group_id'   => 1,
                        'project_id' => 101,
                        'group_name' => 'A:3 - 1x3 ',
                        'created_at' => gmdate('Y-m-d H:i:03'),
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 102,
                        'group_name' => 'A:3 - 1x3 ',
                        'created_at' => gmdate('Y-m-d H:i:03'),
                    ],
                    [
                        'group_id'   => 1,
                        'project_id' => 103,
                        'group_name' => 'A:3 - 1x3 ',
                        'created_at' => gmdate('Y-m-d H:i:03'),
                    ],
                ],
            ],
        ];
    }
    
    public function validBigDataProvider(): array
    {
        $metadata = [];
        {
            $data = [];
            $now  = DateTimeHelper::now();
            
            for ($g = 1; $g <= 3; $g++) {
                for ($i = 1; $i <= 15; $i++) {
                    $pid    = $g * 100 + $i;
                    $data[] = [
                        'group_id'   => $g,
                        'project_id' => $pid,
                        'group_name' => 'K:1 - 3x15',
                        'created_at' => $now,
                    ];
                }
            }
            $metadata[] = $data;
        }
        {
            $data = [];
            $now  = DateTimeHelper::now();
            
            for ($g = 1; $g <= 5; $g++) {
                for ($i = 1; $i <= 5; $i++) {
                    $pid    = $g * 100 + $i;
                    $data[] = [
                        'group_id'   => $g,
                        'project_id' => $pid,
                        'group_name' => 'L:2 - 5x5',
                        'created_at' => $now,
                    ];
                }
            }
            $metadata[] = $data;
        }
        {
            $data = [];
            $now  = DateTimeHelper::now();
            
            for ($g = 1; $g <= 10; $g++) {
                for ($i = 1; $i <= 3; $i++) {
                    $pid    = $g * 100 + $i;
                    $data[] = [
                        'group_id'   => $g,
                        'project_id' => $pid,
                        'group_name' => 'M:3 - 10x3',
                        'created_at' => $now,
                    ];
                }
            }
            $metadata[] = $data;
        }
        {
            $data = [];
            $now  = DateTimeHelper::now();
            
            for ($g = 1; $g <= 20; $g++) {
                for ($i = 1; $i <= 1; $i++) {
                    $pid    = $g * 100 + $i;
                    $data[] = [
                        'group_id'   => $g,
                        'project_id' => $pid,
                        'group_name' => 'N:4 - 20x1',
                        'created_at' => $now,
                    ];
                }
            }
            $metadata[] = $data;
        }
        
        return [$metadata];
    }
    
    public function notestDeleteTable(): void
    {
        $this->porter->setTable($this->tableConfig);
        
        $table = $this->porter->table();
        if ($table->exists()) {
            $table->delete();
            static::assertEquals(false, $table->exists());
        }
    }
}