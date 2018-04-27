<?php
require dirname(__DIR__) . '/autoload.php';

$times = isset($argv[1]) ? (int)$argv[1] : 1000;

const ALLOWED_METHODS_STR = 'ANY,GET,POST,PUT,PATCH,DELETE,OPTIONS,HEAD';
const ALLOWED_METHODS = [
    'ANY',
    'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD',
    // 'COPY', 'PURGE', 'LINK', 'UNLINK', 'LOCK', 'UNLOCK', 'VIEW', 'SEARCH', 'CONNECT', 'TRACE',
];

$times = isset($argv[1]) ? (int)$argv[1] : 1000;

// $arg = 'get';
$arg = ['get', 'post'];

$sample1 = function ($methods) {
    $hasAny = false;
    $methods = \array_map(function ($m) use (&$hasAny) {
        $m = \strtoupper(\trim($m));

        if (!$m || false === \strpos(ALLOWED_METHODS_STR . ',', $m . ',')) {
            throw new \InvalidArgumentException(
                "The method [$m] is not supported, Allow: " . ALLOWED_METHODS_STR
            );
        }

        if (!$hasAny && $m === 'ANY') {
            $hasAny = true;
        }

        return $m;
    }, (array)$methods);

    return $hasAny ? ALLOWED_METHODS : $methods;
};

$sample2 = function ($methods) {
    if (is_string($methods)) {
        $method = strtoupper($methods);

        if ($method === 'ANY') {
            return ALLOWED_METHODS;
        }

        if (false === \strpos(ALLOWED_METHODS_STR . ',', $method . ',')) {
            throw new \InvalidArgumentException(
                "The method [$method] is not supported, Allow: " . ALLOWED_METHODS_STR
            );
        }

        return [$method];
    }

    $upperMethods = [];

    foreach ((array)$methods as $method) {
        $method = strtoupper($method);

        if ($method === 'ANY') {
            return ALLOWED_METHODS;
        }

        if (false === \strpos(ALLOWED_METHODS_STR . ',', $method . ',')) {
            throw new \InvalidArgumentException(
                "The method [$method] is not supported, Allow: " . ALLOWED_METHODS_STR
            );
        }

        $upperMethods[] = $method;
    }

    return $upperMethods;
};

// faster is 2
compare_speed($sample1, $sample2, $times, [
    $arg
]);
