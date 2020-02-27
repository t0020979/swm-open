<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryStorage;

/**
 * Tet A.01 - Create Storage Based on TableName.json File
 */
class GoogleBigQueryStorageBuildTest extends TestCase
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
    
    public function testBuildNotEmpty(): void
    {
        static::assertNotEmpty($this->storage);
    }
    
    public function testBuildAndCheckTableName(): void
    {
        static::assertEquals($this->tableNameFile, $this->storage->tableId());
    }
    
    public function testBuildAndCheckFieldsCount(): void
    {
        $schema = $this->storage->tableSchema();
        static::assertArrayHasKey('schema', $schema);
        static::assertArrayHasKey('fields', $schema['schema']);
        static::assertCount(4, $schema['schema']['fields']);
    }
}