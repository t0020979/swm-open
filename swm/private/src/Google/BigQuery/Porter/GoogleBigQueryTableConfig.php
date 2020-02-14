<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

class GoogleBigQueryTableConfig implements GoogleBigQueryTableConfigInterface
{
    /**
     * @var string
     */
    private $tableId;
    /**
     * @var array
     */
    private $fields;
    /**
     * @var array
     */
    private $credential;
    
    /**
     * @return string
     */
    public function tableId(): string
    {
        return $this->tableId;
    }
    
    /**
     * https://cloud.google.com/bigquery/docs/tables
     * @return array ~ ['schema' => $schema]
     *       $schema ~ ['fields' => $fields]
     *       $fields ~ [
     *                      [
     *                          'name' => 'field1',
     *                          'type' => 'string',
     *                          'mode' => 'requeired'
     *                      ]
     *                      ...
     *                 ]
     */
    public function tableSchema(): array
    {
        return ['schema' => ['fields' => $this->fields]];
    }
    
    public static function overJsonFile($filename): self
    {
        $config = new self();
        if (\file_exists( self::getFilePath($filename) )) {
            $config->credential = json_decode(file_get_contents(self::getFilePath($filename) ), true);
            $config->fields     = $config->credential['fields'];
            $config->tableId    = $filename;
        }
        return $config;
    }
    
    protected static function getFilePath($filename): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . $filename . '.json';
    }
    
}