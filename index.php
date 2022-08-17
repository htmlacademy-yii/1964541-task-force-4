<?php

use TaskForce\importers\CategoryImporter;

require_once 'vendor/autoload.php';

$obj = new CategoryImporter('data/categories.csv', 'test');

$obj->import();

