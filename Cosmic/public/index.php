<?php
ini_set("display_errors",1);
use App\Config;
use Core\Routes;
use Core\QueryBuilder;

if (file_exists(__DIR__ . '/../vendor/usmanhalalit/') && file_exists(__DIR__ . '/../vendor/twig/')) {
    if (!file_exists(__DIR__ . '/uploads/')) {
      
        $createdir = mkdir(__DIR__ . '/uploads/', 0777, true);
        if(!$createdir) {
            echo 'Cant create upload folder, please CHMOD public folder to 777';
            exit;
        }
      
    } elseif (!file_exists(__DIR__ . '/tmp/')) {
        mkdir(__DIR__ . '/tmp/', 0777, true);
    }

} else {
    echo 'Please update composer, vendors are missing!';
    exit;
}

require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 *  Check if configuration exists session
 */

if(!file_exists(__DIR__ . '/../App/Config.php')) {
  
    $copy = copy(__DIR__ . '/../../App/Config.tmp', __DIR__ . '/../../App/Config.php');
    if($copy) {
        redirect('/');
    }
  
    echo 'Cant create config file, please CHMOD App folder to 777 or rename Config.tmp to Config.php';
    exit;
}


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