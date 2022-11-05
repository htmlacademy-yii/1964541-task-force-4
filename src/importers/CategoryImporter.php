<?php

namespace TaskForce\importers;

class CategoryImporter extends AbstractDataImporter
{
    protected string $table_name = 'category';

    /**
     * Возвращает возможные значения для таблицы
     * @return array
     */
    protected function getTableValues(): array
    {

        unset($this->data[0]);
        $string_categories_array = [];
        foreach ($this->data as $categories_array) {
            foreach ($categories_array as $category) {
                $refactored_categories_array[] = '\'' . $category .'\'';
            }
            $string_categories_array[] = implode(',', $refactored_categories_array);
            $refactored_categories_array = [];
        }

        $this->data = $string_categories_array;

        return $this->data;
    }

}
