<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-11-28
 * Time: 下午2:17
 */

$dir = "/var/log/nginx/yyq.chenxiaobo.me_error_log";

$errors = file_get_contents($dir);

echo str_replace(PHP_EOL, '<br/>', $errors);