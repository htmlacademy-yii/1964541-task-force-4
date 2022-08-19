<?php

namespace TaskForce\importers;

use ColumnsNameException;
use RuntimeException;
use SourceFileException;
use SqlTransformException;
use Throwable;

abstract class DataImporter
{
    protected string $filename;
    protected string $new_file_name;
    protected object $fileObject;
    protected array $data;
    protected string $table_name;
    protected array $column_names;

    public function __construct($filename, $new_file_name, $column_names)
    {
        $this->filename = $filename;
        $this->new_file_name = $new_file_name;
        $this->column_names = $column_names;
    }

    public function import(): void
    {
        try {
            $this->getDataArr();
            $this->dataIntoSql();
            $this->insertInto();
        } catch (Throwable $e) {
            error_log("Не удалось записать данные. Ошибка: " . $e->getMessage());
        }
    }

    protected function insertInto(): void
    {
        try {
            $new_file = fopen($this->new_file_name . '.sql', "w");
        } catch(RuntimeException $e) {
            throw new SourceFileException("Не удалось создать файл");
        }
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
        if (empty($sql_query_arr)) {
            throw new SqlTransformException("Преобразование в sql не удалось");
        } else {
            $this->data = $sql_query_arr;
        }
    }

    protected function getDataArr(): void
    {
        try {
            $this->fileObject = new \SplFileObject($this->filename);
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        foreach ($this->getNextLine() as $line) {
            $this->data[] = $line;
        }
    }

    abstract protected function getTableValues(): array;

    protected function getTableTitles(): string
    {
        $this->fileObject->rewind();
        if (count($this->column_names) !== count($this->fileObject->fgetcsv())) {
            throw new ColumnsNameException("Количество столбцов не совпадает с заданным файлом");
        }
        return implode(',', $this->column_names);

    }

    protected function getNextLine(): ?iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return null;
    }
}
