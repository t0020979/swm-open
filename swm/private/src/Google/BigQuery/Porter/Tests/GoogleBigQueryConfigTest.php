<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;

/**
 * Test B.01 - config for porter
 */
class GoogleBigQueryConfigTest extends TestCase
{
    public function testPath(): void
    {
        static::assertTrue( \defined('KEY_PATCH'), 'Constant KEY_PATCH is not Defined');
        static::assertEquals('/app/common/keys/', \KEY_PATCH, 'Constant KEY_PATCH is Change value');
    }
    
    public function testDatasetId(): void
    {
        $porterConfig = new GoogleBigQueryPorterConfig(17, 'ozontest-69f09c82892d.json');
        static::assertEquals('Client_17', $porterConfig->datasetId());
    }
    
    public function testFailConfig(): void
    {
        $porterConfig = new GoogleBigQueryPorterConfig(17, 'ozontest-69f09c82892d.json');
        static::assertArrayHasKey('keyFilePath', $porterConfig->keyFileArray(), 'Cred File probably absent');
    }
    
    public function testInitTestConfig(): void
    {
        $porterConfig = new GoogleBigQueryPorterConfig(
            17,
            'sw-test-20-e97d669c29ba.json',
            '/app/private/src/Google/BigQuery/Porter/Tests/'
        );
        static::assertArrayHasKey('keyFilePath', $porterConfig->keyFileArray(), 'Cred File probably absent');
    }
}