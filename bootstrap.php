<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author simon
 */
// TODO: check include path
//ini_set('include_path', ini_get('include_path'));

// put your code here
spl_autoload_register(function($class) {

    $replace = str_replace('\\', '/', $class);
    $explode = explode('/', $replace, 4);
    end($explode);
    $no_root = current($explode);

    $file = sprintf('%s/lib/%s.php', __DIR__, $no_root);
    if (is_file($file)) {
        require_once $file;
    }
});