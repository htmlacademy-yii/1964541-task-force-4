<?php

namespace TaskForce\importers;

abstract class DataImporter
{
    protected string $filename;
    protected string $new_file_name;
    protected object $fileObject;
    protected array $data;
    protected string $table_name;

    public function __construct($filename, $new_file_name)
    {
        $this->filename = $filename;
        $this->new_file_name = $new_file_name;
    }

    public function import(): void
    {
        $this->getDataArr();
        $this->dataIntoSql();
        $new_file = fopen($this->new_file_name . '.sql', "w");
        fwrite($new_file, 'USE task_force;' . PHP_EOL);
        foreach ($this->data as $sql_query) {
            fwrite($new_file, $sql_query);
        }
        fclose($new_file);
    }

    protected function dataIntoSql(): void
    {
        $sql_query = 'INSERT INTO ' . $this->table_name;
        $sql_query_arr = [];
        foreach ($this->getTableValues() as $value) {
            $sql_query_arr[] = $sql_query . ' (' . $this->getTableTitles() . ') VALUE ' . '(' . $value . ');' . PHP_EOL;
        }
        $this->data = $sql_query_arr;
    }

    protected function getDataArr(): void
    {
        $this->fileObject = new \SplFileObject($this->filename);

        foreach ($this->getNextLine() as $line) {
            $this->data[] = $line;
        }
    }

    abstract protected function getTableValues(): array;

    protected function getTableTitles(): string
    {
        $this->fileObject->rewind();
        return implode(',', $this->fileObject->fgetcsv());
    }

    protected function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $result = $this->fileObject->fgetcsv();
        }

        return $result;
    }
}
