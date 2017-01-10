<?php
/**
 * 微信sdk自动加载类库
 * @author yyq
 */
spl_autoload_register(function($class) {
    require(sprintf("%s/%s.php", dirname(__DIR__), str_replace('\\', '/', $class)));
});