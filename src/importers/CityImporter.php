<?php

namespace TaskForce\importers;

class CityImporter extends AbstractDataImporter
{
    protected string $table_name = 'city';

    /**
     * Возвращает возможные значения для таблицы
     * @return array
     */
    protected function getTableValues(): array
    {
        unset($this->data[0]);

        $string_cities_array = [];
        foreach ($this->data as $city_info_array) {
            $city_name = '\'' . $city_info_array[0] . '\',';
            unset($city_info_array[0]);
            $string_cities_array[] = $city_name . implode(',', $city_info_array);
        }
        $this->data = $string_cities_array;

        return $this->data;
    }
}
