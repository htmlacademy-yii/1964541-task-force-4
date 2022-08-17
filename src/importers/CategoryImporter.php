<?php

namespace TaskForce\importers;

class CategoryImporter extends DataImporter
{
    protected string $table_name = 'category';

    protected function getTableValues(): array
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
}
