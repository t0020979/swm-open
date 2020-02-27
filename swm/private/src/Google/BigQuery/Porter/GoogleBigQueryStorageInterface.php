<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

interface GoogleBigQueryStorageInterface
{
    /**
     * GBQStorageInterface constructor.
     *
     * @param string $tableNameFile - name of json-File inside private/src/Google/BigQuery/Porter/Schema/...
     */
    public function __construct(string $tableNameFile);
    
    /**
     * tableName - as name of File or TableName as field inside File if exist
     * @return string
     */
    public function tableId(): string;
    
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
    public function tableSchema(): array;
    
    /**
     * https://cloud.google.com/bigquery/streaming-data-into-bigquery#streaminginsertexamples
     * @return array ~ [
     *                   [ 'data' => $data ],
     *                   [ 'data' => $data ],
     *                   ...
     *                 ]
     *               $data ~ [
     *                          'field1' => "value1",
     *                          'field2' => "value2",
     *                          ...
     *                       ]
     */
    public function rows(): array;
}