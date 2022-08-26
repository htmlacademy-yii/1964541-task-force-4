<?php

use TaskForce\importers\CategoryImporter;
use TaskForce\importers\CityImporter;


require_once 'vendor/autoload.php';

$category_importer = new CategoryImporter('data/categories.csv', 'category-test', ['name', 'type']);
$city_importer = new CityImporter('data/cities.csv', 'city-test', ['name', 'lat', 'lng']);

$category_importer->import();
$city_importer->import();



