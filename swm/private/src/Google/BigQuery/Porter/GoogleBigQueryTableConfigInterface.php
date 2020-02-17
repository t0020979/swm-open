<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

interface GoogleBigQueryTableConfigInterface
{
    /**
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
}