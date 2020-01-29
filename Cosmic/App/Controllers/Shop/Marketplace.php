<?php
namespace App\Controllers\Shop;

use App\Config;

use App\Models\Player;
use App\Models\Value;
use App\Models\Core;

use Core\Locale;
use Core\View;

use Library\Json;
use Library\HotelApi;

class Marketplace
{
  
    public function sell()
    {
        $validate = request()->validator->validate([
            'item_id'  => 'required|numeric',
            'currency'  => 'required|numeric',
            'costs'  => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
      
        $item_id    = input()->post('item_id');
        $currency   = input()->post('currency');
        $costs      = input()->post('costs');
  
        if(!Value::ifCurrencyExists($currency)) {
            return Json::encode(["status" => "error", "message" => "Currency does not exist"]);
        }
      
        if(!Value::ifItemExists($item_id, request()->player->id)) {
            return Json::encode(["status" => "error", "message" => "Item does not exists"]);
        }
      
        Value::sellItem($item_id, request()->player->id, $currency, $costs);       
        return Json::encode(["status" => "success", "message" => "Bedankt voor je aankoop blabla", "replacepage" => "marketplace/my/inventory"]);
    }
  
    public function search()
    {
        $items = Value::searchFurni(input()->post('furni_name')->value);
      
        foreach($items as $item) {
            $item->user         = Player::getDataById($item->user_id, ['username', 'look']);
            $item->currency     = Core::getCurrencyByType($item->currency_type)->currency;
            $item->catalog_name = str_replace('*', '_', $item->catalog_name);
        }
      
        return Json::encode($items);
    }
  
    public function catalogue()
    {
        $validate = request()->validator->validate([
            'item_id' => 'required|numeric',
            'state'   => 'required|pattern:^(?:catalogueORmarketplace)$'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
      
        $item_id  = input()->post('item_id')->value;
      
        if(input()->post('state')->value == "marketplace") 
        {
            return Json::encode(["status" => "error", "message" => "Is not implementated yet."]);
          
            $item = Value::getOfferById($item_id); 
            if(empty($item)) {
                return Json::encode(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
            }
          
            $first_item = Value::getFirstItem($item->item_id, request()->player->id);
          
            if($item->timestamp_expire < time()) {
                Value::deleteOffer($item_id);
                return Json::encode(["status" => "error", "message" => Locale::get('shop/marketplace/expired'), "replacepage" => "marketplace/my/inventory"]);
            }
          
            $currency = Player::getCurrencys(request()->player->id)[$item->currency_type];

            if($currency->amount < $item->item_costs) {
                return Json::encode(["status" => "error", "message" => Locale::get('core/notification/not_enough_points')]);
            }
          
            HotelApi::execute('givepoints', ['user_id' => request()->player->id, 'points' => - $item->item_costs, 'type' => $item->currency_type]);
            HotelApi::execute("changeitemowner", ["item_id" => $item->item_id, "new_owner" => request()->player->id, "owner_id" => $item->user_id]);
            return Json::encode(["status" => "success", "message" => Locale::get('shop/marketplace/purchased'), "replacepage" => "marketplace/all/sell"]);
        }
             
        if(input()->post('state')->value == "catalogue") 
        {
              $cat_id  = $cat_id = explode('-', input()->post('page_url')->value)[0];
              $item = Value::getItem($item_id);

              $offer = Value::getValueCategoryById(explode('/', $cat_id)[1]);
              $costs = $item->cost_points - ($offer->discount / 100) * $item->cost_points;
              
              $currency = Player::getCurrencys(request()->player->id)[$item->points_type];
          
              if($currency->amount < $costs) {
                  return Json::encode(["status" => "error", "message" => Locale::get('core/notification/not_enough_points')]);
              }

              HotelApi::execute('givepoints', ['user_id' => request()->player->id, 'points' => - $costs, 'type' => $item->points_type]);
              HotelApi::execute("sendgift", ["user_id" => request()->player->id, "itemid" => $item->id, "message" => Locale::get('shop/marketplace/regards')]);
              return Json::encode(["status" => "success", "message" => Locale::get('shop/marketplace/purchased'), "replacepage" => "marketplace/all/sell"]);
        }
    }
}