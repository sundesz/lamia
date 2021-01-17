<?php

spl_autoload_register(function($className) {

    $extension = '.class.php';
    $prefix = "../";

    require_once $prefix . str_replace("\\", "/" , $className) . $extension;
});
