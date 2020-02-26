<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;

/**
 * Test C.01 - Connect to BQ as Create & Delete Dataset
 * @ATTENTION - DEVELOPER ONLY TEST
 * @package   Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryPorterCrudDatasetTest extends TestCase
{
    /**
     * @var GoogleBigQueryPorter
     */
    private $porter;
    /**
     * @var string
     */
    private $datasetId = 'test_create_dataset';
    private $projectId = 'sw-test-20';
    
    public function setUp()
    {
        parent::setUp();
        $porterConfig = new GoogleBigQueryPorterConfig(
            17,
            'sw-test-20-e97d669c29ba.json',
            '/app/private/src/Google/BigQuery/Porter/Tests/'
        );
        $porterConfig->setDatasetId($this->datasetId);
        $this->porter = new GoogleBigQueryPorter($porterConfig);
    }
    
    public function tearDown()
    {
        $this->porter = null;
        parent::tearDown();
    }
    
    public function testOpenDataset(): void
    {
        $dataset = $this->porter->dataset();
        static::assertEquals($dataset->exists(), true);
        
        $info = $dataset->info();
        static::assertEquals($this->datasetId, $info['datasetReference']['datasetId']);
        
        static::assertEquals($this->projectId, $info['datasetReference']['projectId']);
    }
    
    public function testDeleteDataset(): void
    {
        $dataset = $this->porter->dataset();
        static::assertEquals($dataset->exists(), true);
        
        $dataset->delete();
        static::assertEquals($dataset->exists(), false);
    }
}