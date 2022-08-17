<?php
require_once 'vendor/autoload.php';

$obj = new \TaskForce\DataImporter('data/categories.csv', 'test', 'category');

$obj->import();

