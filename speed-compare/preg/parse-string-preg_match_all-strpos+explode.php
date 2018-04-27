<?php

require dirname(__DIR__) . '/autoload.php';

$times = isset($argv[1]) ? (int)$argv[1] : 1000;

$str = '/50be3774f6/{arg1}/arg2/arg3/{int}/arg5/arg6/{arg7}/arg8/arg9[/850726135a]';

$sample1 = function ($route, array $params) {
    $new = '';
    $matches = [];

    foreach (explode('{', $route) as $k => $item) {
        if ($k === 0) {
            $new .= $item;
            continue;
        }

        if ($pos = strpos($item, '}')) {
            $matches[] = $name = substr($item, 0, $pos);
            $regex = $params[$name] ?? '[^/]+';
            $new .= '(' . $regex . ')' . substr($item, $pos + 1);
        } else {
            $new .= $item;
        }
    }

    return [$new, $matches];
};

$sample2 = function ($route, array $params) {
    \preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $route, $m);

    $matches = $m[1];

    /** @var array[] $m */
    $pairs = [];

    foreach ($m[1] as $name) {
        $regex = $params[$name] ?? '[^/]+';

        // Name the match (?P<arg1>[^/]+)
        // $pairs[$key] = '(?P<' . $name . '>' . $regex . ')';
        $pairs['{' . $name . '}'] = '(' . $regex . ')';
    }

    $route = \strtr($route, $pairs);
    return [$route, $matches];
};

compare_speed($sample1, $sample2, $times, [
    $str,
    [
        'all' => '.*',
        'any' => '[^/]+',        // match any except '/'
        'num' => '[1-9][0-9]*',  // match a number and gt 0
        'int' => '\d+',          // match a number
        'act' => '[a-zA-Z][\w-]+', // match a action name
    ]
]);

