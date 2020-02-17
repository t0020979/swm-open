<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use Google\Cloud\BigQuery\Dataset;
use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryTableConfig;

/**
 * Test 012 - Check Config of Table as - create Table by Schema
 * @ATTENTION - DEVELOPER ONLY TEST
 * @package   Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryTableUsingTest extends TestCase
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
        $this->porter  = GoogleBigQueryPorter::build(
            GoogleBigQueryPorterConfig::overKeyFilePath(
                'sw-test-20-e97d669c29ba.json',
                '/app/private/src/Google/BigQuery/Porter/Tests/'
            )
        );
        $this->dataset = $this->porter->dataset($this->datasetName);
        
        $this->tableConfig = GoogleBigQueryTableConfig::overJsonFile($this->tableSchemaName);
    }
    
    public function tearDown()
    {
        $this->porter      = null;
        $this->dataset     = null;
        $this->tableConfig = null;
        parent::tearDown();
    }
    
    public function testOpenDataset(): void
    {
        static::assertEquals($this->dataset->exists(), true);
        
        $info = $this->dataset->info();
        static::assertEquals($this->datasetName, $info['datasetReference']['datasetId']);
    }
    
    public function testCreateTable(): void
    {
        $this->porter->setTable($this->tableConfig);
        
        $table = $this->porter->table();
        static::assertEquals(true, $table->exists());
        
    }
    
    public function testDeleteTable(): void
    {
        $this->porter->setTable($this->tableConfig);
        
        $table = $this->porter->table();
        if ($table->exists()) {
            $table->delete();
            static::assertEquals(false, $table->exists());
        }
        
    }
}