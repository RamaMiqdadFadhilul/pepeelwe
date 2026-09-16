<?php

spl_autoload_register(function (string $nama_class) {

    $file = __DIR__
        . "/modul/"
        . strtolower($nama_class)
        . ".php";

    if (file_exists($file)) {
        require_once $file;
    }
});