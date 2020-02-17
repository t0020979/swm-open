<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter\Tests;

use PHPUnit\Framework\TestCase;
use Seowork\Google\BigQuery\Porter\GoogleBigQueryPorterConfig;

/**
 * Test 001 - config for porter
 * @package Seowork\Google\BigQuery\Porter\Tests
 */
class GoogleBigQueryConfigTest extends TestCase
{
    public function testPath(): void
    {
        static::assertEquals(true, \defined('KEY_PATCH'), 'Constant KEY_PATCH is not Defined');
        static::assertEquals('/app/common/keys/', \KEY_PATCH, 'Constant KEY_PATCH is Change value');
    }
    
    public function testFailConfig(): void
    {
        $config = GoogleBigQueryPorterConfig::overKeyFilePath('ozontest-69f09c82892d.json');
        static::assertArrayHasKey('keyFilePath', $config->keyFileArray(), 'Cred File probably absent');
    }
    
    public function testInitTestConfig(): void
    {
        $config = GoogleBigQueryPorterConfig::overKeyFilePath(
            'sw-test-20-e97d669c29ba.json',
            '/app/private/src/Google/BigQuery/Porter/Tests/'
        );
        static::assertArrayHasKey('keyFilePath', $config->keyFileArray(), 'Cred File probably absent');
    }
}