<?php
namespace App\Controllers;

use App\Config;

use App\Models\Core;

class Api
{
    public $callback = array();
    public $settings;
    public $krewsList;
  
    public function __construct()
    {
        $this->settings = Core::settings();
    }
  
    public function vote()
    {
        if(isset($this->settings->krews_api_hotel_slug) && isset(request()->player->id))
        {
            if(!isset($_COOKIE['expires_at_seconds']) || $_COOKIE['expires_at_seconds'] < time()) 
            {
                $this->return_to = "https://list.krews.org";
              
                $this->krewsList = json_decode(@file_get_contents($this->return_to . "/api/votes/". $this->settings->krews_api_hotel_slug . "/validate?ip=" . \App\Core::getIpAddress()));
                $this->api_param = $this->settings->krews_api_hotel_slug . "?username=" . request()->player->username;

                if($this->krewsList) {
                    if($this->krewsList->status == 0 && !request()->isAjax()) {
                        redirect($this->return_to . "/vote/" . $this->api_param);
                    }

                    if($this->krewsList->status == 0 && request()->isAjax()) {
                        $this->callback = [
                            'krews_list' => $this->krewsList,
                            'krews_api'  => $this->return_to . "/vote/" . $this->api_param
                        ];
                    }

                    if($this->krewsList->status == 1) {
                        setcookie('expires_at_seconds', $this->krewsList->expires_at_seconds + time(), '/');
                    }
                } else {
                    $this->callback == "not configurated";
                }
            }
        }
        response()->json($this->callback);
    }
  
    public function krews()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], $this->settings->krews_api_useragent) !== false) {
          
            if($this->settings->krews_api_advanced_stats) {
                $statistics = [
                    'catalog_pages_count' => Core::getCatalogPages(),
                    'catalog_items_count' => Core::getCatalogItems(),
                    'users_registered'    => Core::getRegisteredUsers(),
                    'items_count'         => Core::getItems(),
                    'online'              => Core::getOnlineCount()
                ];
            } else {
                $statistics = [
                    'online'              => Core::getOnlineCount()
                ];
            }
            
            response()->json($statistics);
        } else {
            response()->json([
                'error' => 'User Agent does not Match',
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
        }
    }
  
    public function noMatch()
    {
        response()->json([
            'error' => 'User Agent does not Match',
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
    }
  
    public function currencys() 
    {
        response()->json(Core::getCurrencys());
    }

    public function version()
    {
        $version_cosmic = json_decode(@file_get_contents("https://cosmicproject.online/version.txt"));
        $version = json_decode(@file_get_contents(Config::site['path'] . "/version.txt"));
        
        return ($version_cosmic != $version) ? true : false;
    }
}