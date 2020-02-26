<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

class GoogleBigQueryPorterConfig implements GoogleBigQueryPorterConfigInterface
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var string
     */
    private $datasetId;
    
    public function __construct(int $clientId, string $keyFile, string $keyFilePath = null)
    {
        $this->datasetId = 'Client_' . $clientId;
        
        if ($keyFilePath === null) {
            $keyFilePath = \KEY_PATCH;
        }
        
        if (\file_exists($keyFilePath . $keyFile)) {
            $this->config = [
                'keyFilePath' => $keyFilePath . $keyFile,
            ];
        }
    }
    
    public function keyFileArray(): array
    {
        return $this->config;
    }
    
    public function datasetId(): string
    {
        return $this->datasetId;
    }
    
    public function setDatasetId(string $datasetId): void
    {
        $this->datasetId = $datasetId;
    }
}