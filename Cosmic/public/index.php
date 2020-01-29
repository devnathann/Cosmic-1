<?php
Header('Content-Security-Policy: upgrade-insecure-requests');

use App\Config;
use Core\Routes;
use Core\QueryBuilder;

if (!is_dir(__DIR__ . '/../vendor/')) {
    if (!is_dir(__DIR__ . '/uploads/') || is_dir(__DIR__ . '/tmp/')) {
      
        $createUploads = mkdir(__DIR__ . '/uploads/', 0777, true);
        $createTmp = mkdir(__DIR__ . '/tmp/', 0777, true);
      
        if(!$createdir || $createTmp) {
            return 'Cant create upload and tmp folder, please CHMOD public folder to 777';
        }
    } else {
        return 'Please update composer, vendors are missing!';
    }
}

require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 *  Check if configuration exists session
 */

if(!file_exists(__DIR__ . '/../App/Config.php')) {
    $copy = copy(__DIR__ . '/../../App/Config.tmp', __DIR__ . '/../../App/Config.php');
    if(!$copy) {
        echo 'Cant create config file, please CHMOD App folder to 777 or rename Config.tmp to Config.php';
        exit;
    }
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