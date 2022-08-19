<?php

namespace TaskForce\importers;

class CityImporter extends DataImporter
{
    protected string $table_name = 'city';

    protected function getTableValues(): array
    {

        unset($this->data[0]);
        $new = [];
        foreach ($this->data as $data_arr) {
            $city = '\'' . $data_arr[0] . '\',';
            unset($data_arr[0]);
            $new[] = $city . implode(',', $data_arr);
        }
        return $this->data = $new;
    }
}
