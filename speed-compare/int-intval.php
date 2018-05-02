<?php
require dirname(__DIR__) . '/autoload.php';

$times = isset($argv[1]) ? (int)$argv[1] : 1000000;

$sample1 = function ($str) {
    return (int)$str;
};

$sample2 = function ($str) {
     return intval($str);
};

compare_speed($sample1, $sample2, $times, [
    '454646'
]);
