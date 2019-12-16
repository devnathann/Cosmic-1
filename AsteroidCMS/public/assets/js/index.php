<?php
date_default_timezone_set('Europe/Amsterdam');
ini_set("display_errors", 1);

use App\Config;

if (file_exists(__DIR__ . '/../vendor/usmanhalalit/') && file_exists(__DIR__ . '/../vendor/twig/')) {
    if(!file_exists(__DIR__ . '/uploads/')) {
        mkdir(__DIR__ . '/uploads/', 0777, true);
    } elseif(!file_exists(__DIR__ . '/tmp/')) {
        mkdir(__DIR__ . '/tmp/', 0777, true);
    }

    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__.'/../'.Config::view.'/maintenance.html';
    exit;
}

include __DIR__ . '/../Core/Helper.php';


use Core\QueryBuilder;
use Core\Routes;

if(Config::debug)
    ini_set("display_errors", 1);

/**
 *  Set session
 */
session_start();

/**
 *  Set Querybuilder
 */
new Querybuilder;
new Config;

/**
 *  Dispatch URI
 */
Routes::init();