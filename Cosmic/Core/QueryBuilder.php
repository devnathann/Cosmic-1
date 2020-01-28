<?php
namespace Core;

use App\Config;

use Exception;
use PDO;
use Pixie\Connection;

class QueryBuilder {

    public function __construct() {
            $config = [
                'driver'    => Config::driver, // Db driver
                'host'      => Config::host,
                'database'  => Config::database,
                'username'  => Config::username,
                'password'  => Config::password,
                'charset'   => Config::charset, // Optional
                'collation' => Config::collation, // Optional
                'prefix'    => Config::prefix, // Table prefix, optional
                'options'   => [
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ],
            ];
  
            try {
                new Connection('mysql', $config, 'QueryBuilder');
            } catch (Exception $e) {
                return;
            }
    }
}