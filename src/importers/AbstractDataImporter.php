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

    /**
     * Выполняет импорт данных из CSV файла в файл с SQL инструкциями
     * @return void
     */
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

    /**
     * Возвращает список значений таблицы
     * @return array
     */
    abstract protected function getTableValues(): array;

    /**
     * Создает файл SQL и записывает имеющиеся данные в него
     * @return void
     * @throws SourceFileException Ошибка создания файла
     */
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

    /**
     * Переделывает полученные из файла данные в CRUD запросы
     * @return void
     * @throws ColumnsNameException Прокидывается ошибка из функции создания колонок (Кол-во не совпадает)
     * @throws SqlTransformException Не удалось преобразовать данные в CRUD запросы
     */
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

    /**
     * Извлекает данные и записывает их в массив
     * @return void
     * @throws SourceFileException Файл не удалось создать
     */
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

    /**
     * Получает названия слобцов в таблице
     * @return array
     * @throws ColumnsNameException Кол-во столбцов не совпадает с заданными в файле
     */
    protected function getTableTitles(): array
    {
        $this->file_object->rewind();
        if (count($this->column_names) !== count($this->file_object->fgetcsv())) {
            throw new ColumnsNameException("Количество столбцов не совпадает с заданным файлом");
        }
        return $this->column_names;

    }

    /**
     * Генератор, проходится по файлу CSV и извлекает данные по строчке
     * @return iterable|null
     */
    protected function getNextLine(): ?iterable
    {
        while (!$this->file_object->eof()) {
            yield $this->file_object->fgetcsv();
        }

        return null;
    }
}
