<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorter;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;

/**
 * Test 002 - Connect to BQ as Create & Delete Dataset
 * @ATTENTION - DEVELOPER ONLY TEST
 * @package Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryPorterConfigureTest extends TestCase
{
    /**
     * @var GoogleBigQueryPorter
     */
    private $porter;
    /**
     * @var string
     */
    private $datasetName = 'test_create_dataset';
    
    public function setUp()
    {
        parent::setUp();
        $this->porter = GoogleBigQueryPorter::build(
            GoogleBigQueryPorterConfig::overKeyFilePath(
                'sw-test-20-e97d669c29ba.json',
                '/app/private/src/Google/BigQuery/Porter/Tests/'
            )
        );
    }
    
    public function tearDown()
    {
        $this->porter = null;
        parent::tearDown();
    }
    
    public function testOpenDataset(): void
    {
        $dataset = $this->porter->dataset($this->datasetName);
        static::assertEquals($dataset->exists(), true);
        
        $info = $dataset->info();
        static::assertEquals($this->datasetName, $info['datasetReference']['datasetId']);
    }
    
    
    public function testDeleteDataset(): void
    {
        $dataset = $this->porter->dataset($this->datasetName);
        static::assertEquals($dataset->exists(), true);
        
        $dataset->delete();
        static::assertEquals($dataset->exists(), false);
    }
    
    
    
}