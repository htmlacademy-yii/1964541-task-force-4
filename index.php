<?php

use TaskForce\importers\CityImporter;

require_once 'vendor/autoload.php';

$obj = new CityImporter('data/cities.csv', 'test');

$obj->import();

