<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

class GoogleBigQueryStorage implements GoogleBigQueryStorageInterface
{
    /**
     * @var string
     */
    private $tableId;
    /**
     * @var array
     */
    private $tableSchema;
    /**
     * @var array
     */
    private $rows;
    /**
     * @var array
     */
    private $tableFileContent;
    /**
     * @var array
     */
    private $fieldNames;
    /**
     * @var array
     */
    private $artificialFieldNames;
    
    public function __construct(string $tableNameFile)
    {
        $this->tableId = $tableNameFile;
        $this->readJson($tableNameFile);
    }
    
    public function tableId(): string
    {
        return $this->tableId;
    }
    
    public function tableSchema(): array
    {
        return $this->tableSchema;
    }
    
    public function rows(): array
    {
        return $this->rows;
    }
    
    /**
     * @param array $data        [ $row, $row, row, ... ]
     *                           $row  ~ [
     *                           'field1' => "value1",
     *                           'field2' => "value2",
     *                           ...
     *                           ]
     */
    public function fill($data): void
    {
        $pad = [];
        foreach ($data as $row) {
            $record = [];
            foreach ($row as $key => $val) {
                if (in_array($key, $this->fieldNames, true)) {
                    $record[$key] = $val;
                }
            }
            $pad[] = $record;
        }
        
        $pad        = $this->notationCreatedAt($pad);
        $this->rows = $this->decorateData($pad);
    }
    
    // private part
    
    private function getFilePath($filename): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . $filename . '.json';
    }
    
    private function readJson($tableNameFile): void
    {
        if (\file_exists($this->getFilePath($tableNameFile))) {
            $this->tableFileContent = json_decode(file_get_contents($this->getFilePath($tableNameFile)), true);
            if (array_key_exists('tableId', $this->tableFileContent)) {
                $this->tableId = $this->tableFileContent['tableId'];
            }
            
            if (array_key_exists('fields', $this->tableFileContent)) {
                $fields            = $this->tableFileContent['fields'];
                $this->tableSchema = ['schema' => ['fields' => $fields]];
                
                $this->fieldNames = array_column($fields, 'name');
            }
            
            if (array_key_exists('artificial', $this->tableFileContent)) {
                $this->artificialFieldNames = $this->tableFileContent['artificial'];
            }
        }
    }
    
    private function notationCreatedAt($pad): array
    {
        $artificialKey = 'created_at';
        
        if (in_array($artificialKey, $this->artificialFieldNames, true)) {
            $now = gmdate('Y-m-d H:i:s');
            foreach ($pad as &$row) {
                $row[$artificialKey] = $now;
            }
        }
        
        return $pad;
    }
    
    private function decorateData($pad): array
    {
        $rows = [];
        foreach ($pad as $row) {
            $rows[] = ['data' => $row];
        }
        
        return $rows;
    }
}