<?php

namespace TaskForce;

class DataImporter
{
    private string $filename;
    private string $new_file_name;
    private object $fileObject;
    private array $data;
    private string $table_name;

    public function __construct($filename, $new_file_name, $table_name)
    {
        $this->filename = $filename;
        $this->table_name = $table_name;
        $this->new_file_name = $new_file_name;
    }

    public function import(): void
    {
        $this->getDataArr();
        $this->dataIntoSql();
        $new_file = fopen($this->new_file_name . '.sql', "w");
        fwrite($new_file, 'USE task_force;
        ');
        foreach ($this->data as $sql_query) {
            fwrite($new_file, $sql_query);
        }
        fclose($new_file);
    }

    private function dataIntoSql(): void
    {
        $sql_query = 'INSERT INTO ' . $this->table_name;
        $sql_query_arr = [];
        foreach ($this->getTableValues() as $value) {
            $sql_query_arr[] = $sql_query . ' (' . $this->getTableTitles() . ') VALUE ' . '(' . $value . ');
            ';
        }
        $this->data = $sql_query_arr;
    }

    private function getDataArr(): void
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
            foreach ($data_arr as $item) {
                $new_arr[] = '\'' . $item .'\'';
            }
            $new[] = implode(',', $new_arr);
            $new_arr = [];
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
