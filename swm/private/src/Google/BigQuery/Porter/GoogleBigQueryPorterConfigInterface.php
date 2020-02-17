<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

interface GoogleBigQueryPorterConfigInterface
{
    /**
     * other  [ 'keyFilePath' => KEY_PATH . $keyFile, ]
     * or     [ 'keyFile'     => json_decode(file_get_contents($path), true) ]
     * @return array
     */
    public function keyFileArray(): array;
    
}