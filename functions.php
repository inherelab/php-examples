<?php
/**
 * some common functions
 */

function pretty_echo(string $msg, string $style = 'green', $nl = false)
{
    static $styles = [
        'yellow' => '1;33',
        'magenta' => '1;35',
        'white' => '1;37',
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'brown' => '0;33',
        'blue' => '0;34',
        'cyan' => '0;36',

        'light_red' => '1;31',
        'light_blue' => '1;34',
        'light_gray' => '37',
        'light_green' => '1;32',
        'light_cyan' => '1;36',
    ];

    if (isset($styles[$style]) && false === strpos(PHP_OS, 'WIN')) {
        return sprintf("\033[%sm%s\033[0m" . ($nl ? PHP_EOL : ''), $styles[$style], $msg);
    }

    return $msg . ($nl ? PHP_EOL : '');
}

function print_json($ret, $echo = true)
{
    $str = json_encode($ret, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    if ($echo) {
        return $str;
    }

    echo $str . PHP_EOL;
}


function compare_speed(callable $sample1, callable $sample2, int $times = 1000, array $args = [])
{
    if ($times < 1000) {
        $times = 1000;
    }

    $start1 = microtime(1);

    // test 0
    for ($i = 0; $i < $times; $i++) {
        $sample1(...$args);
    }

    $end1 = microtime(1);

    // test 1
    $start2 = microtime(1);

    for ($i = 0; $i < $times; $i++) {
        $sample2(...$args);
    }

    $end2 = microtime(1);

    // calc total
    $total1 = round($end1 - $start1, 3);
    $total2 = round($end2 - $start2, 3);

    // average
    $decimal = 3;
    $average1 = round($total1/$times, $decimal);
    $average2 = round($total2/$times, $decimal);

    $result1 = $sample1(...$args);
    $result2 = $sample2(...$args);

    printf("Sample 1 exec results: %s\n", var_export($result1, true));
    printf("Sample 2 exec results: %s\n", var_export($result2, true));

    $faster = $total1 - $total2 > 0 ? 'Sample 2' : 'Sample 1';

    printf(
        "\n\t              Speed Test Results(Faster is: %s)\n%s\n",
        $faster, str_repeat('---', 29)

    );

    $template = "%-12s %-22s %-25s %-20s\n";
    $results = [
        ['Test Name', 'Number of executions', 'Total time-consuming(us)', 'Average time-consuming(us)'],
        ['Sample 1', $times, $total1, $average1],
        ['Sample 2', $times, $total2, $average2],
    ];

    foreach ($results as $items) {
        printf($template, ...$items);
    }

    echo "\n";
}
