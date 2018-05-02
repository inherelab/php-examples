<?php
require dirname(__DIR__, 2) . '/autoload.php';

$times = isset($argv[1]) ? (int)$argv[1] : 10000;

$sample1 = function ($route) {
    // '/hello[/{name}]' -> '/hello(?:/{name})?'
    return str_replace(['[', ']'], ['(?:', ')?'], $route);
};

$sample2 = function ($route) {
    return strtr($route, [
        '[' => '(?:',
        ']' =>  ')?'
    ]);
};

compare_speed($sample1, $sample2, $times, [
    '/hello[/{name}]'
]);

/*
Sample 1 exec results: '/hello(?:/{name})?'
Sample 2 exec results: '/hello(?:/{name})?'

                  Speed Test Results(Faster is: Sample 2)
---------------------------------------------------------------------------------------
Test Name    Number of executions   Total time-consuming(us)  Average time-consuming(us)
Sample 1     10000                  0.028                     0
Sample 2     10000                  0.027                     0
 */
