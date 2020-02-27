<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\InsertResponse;
use Google\Cloud\BigQuery\Table;

class GoogleBigQueryPorter
{
    /**
     * @var BigQueryClient
     */
    private $bigQuery;
    /**
     * @var GoogleBigQueryPorterConfig
     */
    private $porterConfig;
    /**
     * @var Dataset
     */
    private $dataset;
    /**
     * @var string - name of dataset saved in current transaction
     */
    private $datasetId;
    /**
     * @var bool - true if DataSet was just Created for current transaction
     */
    private $isDatasetNew;
    /**
     * @var GoogleBigQueryStorage
     */
    private $storage;
    /**
     * @var Table
     */
    private $table;
    /**
     * @var bool - true id Table was just Created for current transaction
     */
    private $isTableNew;
    
    public function __construct(GoogleBigQueryPorterConfigInterface $porterConfig)
    {
        $this->porterConfig = $porterConfig;
        $this->bigQuery     = new BigQueryClient($porterConfig->keyFileArray());
        $this->initDataset();
    }
    
    /**
     * Find Or Create DataSet by it Name
     * https://cloud.google.com/bigquery/docs/datasets
     */
    protected function initDataset(): void
    {
        $this->datasetId = $this->porterConfig->datasetId();
        $this->dataset   = $this->bigQuery->dataset($this->datasetId);
        if (!$this->dataset->exists()) {
            $this->dataset      = $this->bigQuery->createDataset($this->datasetId);
            $this->isDatasetNew = true;
        } else {
            $this->isDatasetNew = false;
        }
    }
    
    public function dataset(): Dataset
    {
        return $this->dataset;
    }
    
    public function datasetId(): string
    {
        $info = $this->dataset->info();
        
        return $info['datasetReference']['datasetId'];
    }
    
    public function projectId(): string
    {
        $info = $this->dataset->info();
        
        return $info['datasetReference']['projectId'];
    }
    
    /**
     * Find Or Create Table by it Name
     * https://cloud.google.com/bigquery/docs/tables
     *
     * @param GoogleBigQueryStorageInterface $storage
     */
    protected function initTable(GoogleBigQueryStorageInterface $storage): void
    {
        $this->storage = $storage;
        $this->table   = $this->dataset->table($this->storage->tableId());
        if (!$this->table->exists()) {
            $this->table      = $this->dataset->createTable($this->storage->tableId(), $this->storage->tableSchema());
            $this->isTableNew = true;
        } else {
            $this->isTableNew = false;
        }
    }
    
    /**
     * @param GoogleBigQueryStorageInterface $storage
     *
     * @return Table
     */
    public function table(GoogleBigQueryStorageInterface $storage = null): Table
    {
        if ($storage !== null && $storage !== $this->storage) {
            $this->initTable($storage);
        }
        
        return $this->table;
    }
    
    /**
     * @param array[] $data
     *
     * @return InsertResponse
     */
    public function upload(array $data = null): InsertResponse
    {
        if ($data !== null) {
            $this->storage->fill($data);
        }
        
        return $this->table->insertRows($this->storage->rows());
    }
}