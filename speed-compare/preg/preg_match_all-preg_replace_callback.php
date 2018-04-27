<?php
require dirname(__DIR__) . '/autoload.php';

$times = isset($argv[1]) ? (int)$argv[1] : 1000;

$str = '/50be3774f6/{arg1}/arg2/arg3/{int}/arg5/arg6/{arg7}/arg8/arg9[/850726135a]';

$sample1 = function ($route, $params) {
    if (\preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $route, $m)) {
        /** @var array[] $m */
        $pairs = [];

        foreach ($m[1] as $name) {
            $regex = $params[$name] ?? '[^/]+';
            $pairs['{' . $name . '}'] = '(' . $regex . ')';
        }

        $route = \strtr($route, $pairs);
        $conf['matches'] = $m[1];
    }

    return $route;
};

$sample2 = function ($route, $params) {
    $route = (string)preg_replace_callback('#\{([a-zA-Z_][\w-]*)\}#', function ($m) use ($params) {
        // var_dump($m, $params);die;
        return '(' . ($params[$m[1]] ?? '[^/]+') . ')';
    }, $route);

    return $route;
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
