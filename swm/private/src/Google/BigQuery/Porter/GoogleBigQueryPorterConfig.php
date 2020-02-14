<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

class GoogleBigQueryPorterConfig implements GoogleBigQueryPorterConfigInterface
{
    /**
     * @var array
     */
    private $config;
    
    public function keyFileArray(): array
    {
        return $this->config;
    }
    
    public static function overKeyFilePath(string $keyFile, $keyFilePath = null): self
    {
        $porterConfig         = new self();
        $porterConfig->config = [];
        
        if ($keyFilePath === null) {
            $keyFilePath = \KEY_PATCH;
        }
        
        if (\file_exists($keyFilePath . $keyFile)) {
            $porterConfig->config = [
                'keyFilePath' => $keyFilePath . $keyFile,
            ];
        }
        
        return $porterConfig;
    }
}