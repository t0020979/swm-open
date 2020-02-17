<?php

declare(strict_types=1);

namespace Seowork\Google\BigQuery\Porter;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\Table;

/**
 * Class GoogleBigQueryPorter
 * @package Seowork\Google\BigQuery\Porter
 */
class GoogleBigQueryPorter
{
    /**
     * @var BigQueryClient
     */
    private $bigQuery;
    //
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
    //
    /**
     * @var GoogleBigQueryTableConfigInterface
     */
    private $tableConfig;
    /**
     * @var Table
     */
    private $table;
    /**
     * @var bool - true id Table was just Created for current transaction
     */
    private $isTableNew;
    //
    
    /**
     * Find Or Create DataSet by it Name
     * https://cloud.google.com/bigquery/docs/datasets
     *
     * @param string $datasetName
     */
    protected function initDataset($datasetName): void
    {
        $this->datasetId = $datasetName;
        $this->dataset   = $this->bigQuery->dataset($datasetName);
        if (!$this->dataset->exists()) {
            $this->dataset      = $this->bigQuery->createDataset($datasetName);
            $this->isDatasetNew = true;
        } else {
            $this->isDatasetNew = false;
        }
    }
    
    /**
     * Alias for chain: Find Or Create DataSet by it Name
     *
     * @param string $datasetName
     *
     * @return self
     */
    public function setDataset($datasetName): self
    {
        $this->initDataset($datasetName);
        
        return $this;
    }
    
    public function dataset($datasetName = null): Dataset
    {
        if ($datasetName !== null) {
            $this->initDataset($datasetName);
        }
        
        return $this->dataset;
    }
    
    public function datasetId(): string
    {
        $info = $this->dataset->info();
        
        return $info['datasetReference']['datasetId'];
    }
    
    public function projectsId(): string
    {
        $info = $this->dataset->info();
        
        return $info['datasetReference']['projectsId'];
    }
    
    //
    
    /**
     * Find Or Create Table by it Name
     * https://cloud.google.com/bigquery/docs/tables
     *
     * @param GoogleBigQueryTableConfigInterface $tableConfig
     */
    protected function initTable($tableConfig): void
    {
        $this->tableConfig = $tableConfig;
        $this->table       = $this->dataset->table($this->tableId());
        if (!$this->table->exists()) {
            $this->table      = $this->dataset->createTable($this->tableId(), $this->tableSchema());
            $this->isTableNew = true;
        } else {
            $this->isTableNew = false;
        }
    }
    
    /**
     * Alias for chain: Find Or Create Table by it Name
     *
     * @param GoogleBigQueryTableConfigInterface $tableConfig
     *
     * @return GoogleBigQueryPorter
     */
    public function setTable($tableConfig): self
    {
        $this->initTable($tableConfig);
        
        return $this;
    }
    
    /**
     * @param GoogleBigQueryTableConfigInterface $tableConfig
     *
     * @return Table
     */
    public function table($tableConfig = null): Table
    {
        if ($tableConfig !== null) {
            $this->initTable($tableConfig);
        }
        
        return $this->table;
    }
    
    public function tableId(): string
    {
        return $this->tableConfig->tableId();
    }
    
    public function tableSchema(): array
    {
        return $this->tableConfig->tableSchema();
    }
    
    //
    // @TODO перед этим должен быть вызван метод подготовки данных, в котором будет заполнено поле created_at
    public function upload($data): void
    {
        $rows = [];
        foreach ($data as $row) {
            $rows[] = ['data' => $row];
        }
    
        $this->table->insertRows($rows);
    
    }
    
    public function update($context): void
    {
    }
    
    public static function build(GoogleBigQueryPorterConfigInterface $config): self
    {
        $porter           = new self();
        $porter->bigQuery = new BigQueryClient($config->keyFileArray());
        
        return $porter;
    }
}