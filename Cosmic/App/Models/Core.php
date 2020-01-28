<?php
namespace App\Models;

use PDO;
use QueryBuilder;

class Core
{
    public static function settings()
    {
        $settings = QueryBuilder::table('website_settings')->get();
      
        $inArray = new \stdClass();
      
        foreach($settings as $setting) {
            if(!empty($setting->value) && !is_null($setting->value)) {
                $key           = $setting->key;
                $inArray->$key = $setting->value;
            }
        }
      
        return $inArray;
    }
  
    public static function getCurrencys($array = false)
    {
        return QueryBuilder::table('website_settings_currencys')->get();
    }
  
    public static function getCurrencyByType($type)
    {
        return QueryBuilder::table('website_settings_currencys')->where('type', $type)->first();
    }
  
    public static function getRegisteredUsers()
    {
        return QueryBuilder::table('users')->count();
    }
  
    public static function getCatalogPages()
    {
        return QueryBuilder::table('catalog_pages')->count();
    }
  
    public static function getCatalogItems()
    {
        return QueryBuilder::table('catalog_items')->count();
    }
 
    public static function getItems()
    {
        return QueryBuilder::table('items_base')->count();
    }

    public static function getOnlineCount()
    {
        return QueryBuilder::table('users')->where('online', "1")->count();
    }
}