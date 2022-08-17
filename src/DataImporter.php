<?php

namespace TaskForce;

class DataImporter
{
    private string $filename;
    private object $fileObject;
    private array $data;
    private string $table_name;

    public function __construct($filename, $table_name)
    {
        $this->filename = $filename;
        $this->table_name = $table_name;
    }

    public function import(): array
    {
        $this->getDataArr();
        $this->dataIntoSql();
        return $this->data;
    }

    private function dataIntoSql(): void
    {
        $sql_query = 'INSERT INTO ' . $this->table_name;
        $sql_query_arr = [];
        foreach ($this->getTableValues() as $value) {
            $sql_query_arr[] = $sql_query . ' (' . $this->getTableTitles() . ') VALUE ' . '(' . $value . ')';
        }
        $this->data = $sql_query_arr;
    }

    private function getDataArr()
    {
        $this->fileObject = new \SplFileObject($this->filename);

        foreach ($this->getNextLine() as $line) {
            $this->data[] = $line;
        }
    }

    private function getTableValues(): array
    {
        unset($this->data[0]);
        $new = [];
        foreach ($this->data as $data_arr) {
            $new[] = implode(',', $data_arr);
        }
        return $this->data = $new;
    }

    private function getTableTitles(): string
    {
        $this->fileObject->rewind();
        return implode(',', $this->fileObject->fgetcsv());
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $result = $this->fileObject->fgetcsv();
        }

        return $result;
    }
}
