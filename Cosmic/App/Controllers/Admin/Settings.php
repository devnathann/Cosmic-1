<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Player;
use App\Models\Core;
use App\Models\Permission;

use Core\View;

use Library\Json;

class Settings
{
    public function save()
    {
        foreach(input()->all() as $column => $value) {
            Admin::saveSettings($column, $value);
        }
      
        return Json::encode(["status" => "success", "message" => "Saved!"]);
    }
  
    public function addCurrency()
    {
        $currency = input()->post('currency')->value;
        $type = input()->post('type')->value;
        $amount = input()->post('amount')->value;
      
        Core::addCurrency($currency, $type, $amount);
        return Json::encode(["status" => "success", "message" => "Currency has been added!"]);
    }
  
    public function deleteCurrency()
    {
        if(Core::deleteCurrency(input()->post('type')->value, input()->post('currency')->value)) {
            return Json::encode(["status" => "success", "message" => "Currency has been deleted"]);
        }
    }
  
    public function getCurrencys()
    {
        return Json::encode(Core::getCurrencys());
    }
  
    public function view()
    {
        $settings = Core::settings();
        $settings->vip_badges = json_decode($settings->vip_badges,true);
      
        $settings->vip_currency_type = Core::getCurrencyByType($settings->vip_currency_type);
        $settings->namechange_currency_type = Core::getCurrencyByType($settings->namechange_currency_type);
      
        $settings->ranks = Permission::getRanks();
        $settings->user_of_the_week = Player::getDataById($settings->user_of_the_week ?? 0, ['id', 'username']) ?? false;

        View::renderTemplate('Admin/Management/settings.html', ['settings' => $settings, 'permission' => 'housekeeping_config']);
    }
}