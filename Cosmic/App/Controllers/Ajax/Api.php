<?php
namespace App\Controllers\Ajax;

use App\Config;

use App\Models\Core;

class Api
{
    public static function krews()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], Core::settings()->krews_api_useragent) !== true) {
          
            if(Config::krewsApi['advanced_stats']) {
                $statistics = array(
                    'catalog_pages_count' => Core::getCatalogPages(),
                    'catalog_items_count' => Core::getCatalogItems(),
                    'users_registered'    => Core::getRegisteredUsers(),
                    'items_count'         => Core::getItems(),
                    'online'              => Core::getOnlineCount()
                );
            } else {
                $statistics = array(
                    'online'              => Core::getOnlineCount()
                );
            }
            
            response()->json($statistics);
        } else {
            response()->json([
                'error' => 'User Agent does not Match',
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
        }
    }
  
    public static function currencys() 
    {
        response()->json(Core::getCurrencys());
    }
  
  
}