<?php
use App\Config;
use Core\Routes;
use Core\QueryBuilder;

include_once __DIR__ . '/../Core/Helper.php';
include_once __DIR__ . '/../vendor/autoload.php';

if(Config::debug) {
    ini_set("display_errors", 1);
}

/**
 *  Set session
 */

session_start();

/**
 *  Set QueryBuilder
 */

new Querybuilder;
new Config;

/**
 *  Dispatch URI
 */
Routes::init();
