<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryTableConfig;

/**
 * Test 011 - Class Config for Table of BigQuery
 * @package Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryTableConfigTest extends TestCase
{
    public function testInitTestConfig(): void
    {
        $filename = 'groups_by_projects';
        $config = GoogleBigQueryTableConfig::overJsonFile($filename);

        static::assertNotEmpty($config);
        static::assertEquals($filename, $config->tableId());
        
        $schema = $config->tableSchema();
        static::assertEquals(4, count( $schema['schema']['fields']) );
    }

}