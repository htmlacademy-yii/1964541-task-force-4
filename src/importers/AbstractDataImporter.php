<?php

namespace TaskForce\importers;

use RuntimeException;
use SplFileObject;
use TaskForce\exceptions\ColumnsNameException;
use TaskForce\exceptions\SourceFileException;
use TaskForce\exceptions\SqlTransformException;
use Throwable;

abstract class AbstractDataImporter
{
    protected string $filename;
    protected string $new_file_name;
    protected object $file_object;
    protected array $data;
    protected string $table_name;
    protected array $column_names;

    public function __construct(string $filename, string $new_file_name, array $column_names)
    {
        $this->filename = $filename;
        $this->new_file_name = $new_file_name;
        $this->column_names = $column_names;
    }

    public function import(): void
    {
        try {
            $this->extractDataArr();
            $this->dataIntoSql();
            $this->insertInto();
        } catch (Throwable $e) {
            error_log("Не удалось записать данные. Ошибка: " . $e->getMessage());
        }
    }

    protected function insertInto(): void
    {
        try {
            $new_file = new SplFileObject($this->new_file_name . '.sql', 'w');;
        } catch(RuntimeException $e) {
            throw new SourceFileException("Не удалось создать файл");
        }

        $new_file->fwrite('USE task_force;' . PHP_EOL);

        foreach ($this->data as $sql_query) {
            $new_file->fwrite($sql_query);
        }
        $new_file = null;
    }

    protected function dataIntoSql(): void
    {
        $sql_query = 'INSERT INTO ' . $this->table_name;
        $sql_query_arr = [];
        foreach ($this->getTableValues() as $value) {
            $sql_query_arr[] = $sql_query . ' (' . implode(',', $this->getTableTitles()) . ') VALUE ' . '(' . $value . ');' . PHP_EOL;
        }
        if (empty($sql_query_arr)) {
            throw new SqlTransformException("Преобразование в sql не удалось");
        } else {
            $this->data = $sql_query_arr;
        }
    }

    protected function extractDataArr(): void
    {
        try {
            $this->file_object = new \SplFileObject($this->filename);
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        foreach ($this->getNextLine() as $line) {
            $this->data[] = $line;
        }
    }

    abstract protected function getTableValues(): array;

    protected function getTableTitles(): array
    {
        $this->file_object->rewind();
        if (count($this->column_names) !== count($this->file_object->fgetcsv())) {
            throw new ColumnsNameException("Количество столбцов не совпадает с заданным файлом");
        }
        return $this->column_names;

    }

    protected function getNextLine(): ?iterable
    {
        while (!$this->file_object->eof()) {
            yield $this->file_object->fgetcsv();
        }

        return null;
    }
}
