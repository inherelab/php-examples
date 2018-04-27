<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/5/28
 * Time: 下午10:36
 */

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Shanghai');

require __DIR__ . '/functions.php';

$baseDir = __DIR__;

$map = [
    'Toolkit\Examples\\' => __DIR__ . '/classes',
];

spl_autoload_register(function($class) use ($map)
{
    foreach ($map as $np => $dir) {
        if (0 === strpos($class, $np)) {
            $path = str_replace('\\', '/', substr($class, strlen($np)));
            $file = $dir . "/{$path}.php";

            if (is_file($file)) {
                _include_file($file);
            }
        }
    }
});

function _include_file($file) {
    include $file;
}
