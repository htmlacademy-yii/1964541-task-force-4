<?php
require_once 'vendor/autoload.php';

$obj = new \TaskForce\DataImporter('data/cities.csv');

$obj->import();
$arr = $obj->getValues();

var_dump($arr);
