<?php
require_once 'vendor/autoload.php';

$obj = new \TaskForce\DataImporter('data/cities.csv', 'city');

$arr = $obj->import();

var_dump($arr);
