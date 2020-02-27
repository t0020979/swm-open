<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use Google\Cloud\BigQuery\Dataset;
use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryStorage;

/**
 * Test C.02 - Check Config of Table as - create Table by Schema and Delete Table
 * @ATTENTION - DEVELOPER ONLY TEST
 */
class GoogleBigQueryPorterCrudTableTest extends TestCase
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
            'sw-test-20-e97d669c29ba.json',
            '/app/private/src/Google/BigQuery/Porter/Tests/'
        );
        $porterConfig->setDatasetId($this->datasetId);
        
        $this->porter  = new GoogleBigQueryPorter($porterConfig);
        $this->dataset = $this->porter->dataset();
        $this->storage = new GoogleBigQueryStorage($this->tableNameFile);
    }
    
    public function tearDown()
    {
        $this->porter  = null;
        $this->dataset = null;
        $this->storage = null;
        parent::tearDown();
    }
    
    public function testOpenDataset(): void
    {
        static::assertTrue($this->dataset->exists());
        
        $info = $this->dataset->info();
        static::assertEquals($this->datasetId, $info['datasetReference']['datasetId']);
    }
    
    public function testCreateTable(): void
    {
        $table = $this->porter->table($this->storage);
        static::assertTrue($table->exists());
    }
    
    public function testDeleteTable(): void
    {
        $table = $this->porter->table($this->storage);
        if ($table->exists()) {
            $table->delete();
            static::assertNotTrue( $table->exists());
        }
    }
}