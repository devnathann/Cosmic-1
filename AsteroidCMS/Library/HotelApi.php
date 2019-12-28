<?php
namespace Library;

use App\Config;

class HotelApi
{ 
    public static function execute($param, $data = null)
    {
        if(!Config::apiEnabled) {
            echo '{"status":"error","message":"Socket API has been disabled"}';
            exit;
        }
      
        if (!function_exists('socket_create')){
            echo '{"status":"error","message":"Please enable sockets in your php.ini!"}';
            exit;
        }
      
        $rconPort = Config::apiPort;
        $rconHost = Config::apiHost;
      
        $data = json_encode(array('key' => $param, 'data' => $data));

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      
        if ($socket === false) {
            echo '{"status":"error","message":"socket_create() failed: reason: ' . socket_strerror(socket_last_error()) . '"}';
            exit;
        }

        $result = socket_connect($socket, $rconHost, $rconPort);
        if ($result === false) {
            echo '{"status":"error","message":"socket_connect() failed.\nReason:  ' . socket_strerror(socket_last_error($socket)) . '"}';
            exit;
        }

        if(socket_write($socket, $data, strlen($data)) === false){
            echo '{"status":"error","message":"' . socket_strerror(socket_last_error($socket)) . '"}';
            exit;
        }

        $out = socket_read($socket, 2048);
    }
}