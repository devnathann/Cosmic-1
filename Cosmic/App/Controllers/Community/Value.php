<?php
namespace App\Controllers\Community;

use App\Config;

use App\Models\Core;
use App\Models\Player;
use App\Models\Value as Values;

use Core\Locale;
use Core\View;

use Library\Json;

class Value
{
    public function sell()
    {
        $items = Values::allSellItems();
        
        foreach($items as $item) {
            $item->catalog_name = str_replace('*', '_', $item->catalog_name);
            $item->currency     = Core::getCurrencyByType($item->currency_type)->currency;
            $item->user         = Player::getDataById($item->user_id, ['username', 'look']);
        }
        
        View::renderTemplate('Shop/Marketplace/sell.html', [
            'title'           => Locale::get('core/title/community/value'),
            'page'            => 'community_value',
            'searchbar'       => true,
            'items'           => $items,
            'currency_types'  => Core::getCurrencys()
        ]);
    }
  
    public function my()
    {
        $data = new \stdClass();
      
        $data->offers = Values::mySales(request()->player->id);
      
        foreach($data->offers as $item) {
            if($item->timestamp_expire < time()) {
                if(Values::deleteOffer($item->id)) {
                    $data->offers = Values::mySales(request()->player->id);
                }
            }
        }
      
        $data->items = Values::myItems(request()->player->id);
      
        foreach($data->items as $item) {
            $countItems = Values::ifMyItemExists($item->item_id, request()->player->id);
            if($countItems){
                $item->count = $item->count - $countItems;
            }
        }
      
        View::renderTemplate('Shop/Marketplace/my.html', [
            'title'       => Locale::get('core/title/community/value'),
            'page'        => 'community_value',
            'my_items'    => $data->items,
            'my_sales'    => $data->offers
        ]);
    }
  
    public function index($slug = null, $cat_id = null)
    {      
        if(empty($slug)) {
            $cat_id = Values::getFirstRare();
          
            if(!empty($cat_id)) {
                if(request()->isAjax()) {
                    return Json::encode(["status" => "success", "replacepage" => "marketplace/{$cat_id->id}-{$cat_id->slug}"]); 
                }

                redirect('/marketplace/' . $cat_id->id . '-' . $cat_id->slug);
            } else {
                redirect('/');
            }
        } else {
            $cat_id = explode('-', $slug)[0];
        }
      
        $values = Values::getValueCategoryById($cat_id->id ?? $cat_id);
        $allItems = Values::getValues($values, true);
      
        foreach($allItems as $item) {
            $item->currency = Core::getCurrencyByType($item->points_type);
            $item->price = $costs = $item->cost_points - ($values->discount / 100) * $item->cost_points;
        }
      
        $categories = Values::getValueCategorys();

        View::renderTemplate('Community/value.html', [
            'title'           => Locale::get('core/title/community/value'),
            'page'            => 'community_value',
            'values'          => $allItems,
            'cat'             => $values ?? $cat_id,
            'categories'      => $categories,
            'currency_types'  => Core::getCurrencys()
        ]);
    }
}