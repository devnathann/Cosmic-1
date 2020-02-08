<?php
namespace App\Controllers\Admin;

use App\Config;

use App\Models\Admin;
use App\Models\Log;
use App\Models\Player;
use App\Models\Core;
use App\Models\Shop as Shops;

use Core\View;

use Library\HotelApi;
use Library\Json;

class Shop
{
    public function editcreate()
    {
        $validate = request()->validator->validate([
            'currencys' => 'required',
            'amount'    => 'required|numeric',
            'price'     => 'required|numeric',
            'offer_id'  => 'required',
            'private_key' => 'required'
        ]);
      
        $id = input()->post('shopId')->value;
        $currencys = input()->post('currencys')->value;
        $amount = input()->post('amount')->value;
        $price = input()->post('price')->value;
        $offer_id = input()->post('offer_id')->value;
        $private_key = input()->post('private_key')->value;
      
        if(!$validate->isSuccess()) {
            return;
        }
      
        if (!empty($id)) {
            Admin::offerEdit($id, $currencys, $amount, $price, $offer_id, $private_key);
            Log::addStaffLog('-1', 'Shop edited: ' . $offer_id, request()->player->id, 'shop');
            response()->json(["status" => "success", "message" => "Shop edited successfully!"]);
        }
      
        Admin::offerCreate($currencys, $amount, $price, $offer_id, $private_key);
        Log::addStaffLog('-1', 'Shop item created: ' . $offer_id, request()->player->id, 'shop');
        response()->json(["status" => "success", "message" => "Shop created successfully!"]);
    }
  
    public function getOfferById()
    {
       $validate = request()->validator->validate([
            'post'        => 'required'
        ]);

        if (!$validate->isSuccess()) {
            return;
        }
      
        $offer = Shops::getOfferById(input()->post('post')->value);
        response()->json($offer);
    }

    public function getOffers()
    {
        $offers = Admin::getOffers();
        foreach($offers as $offer) {
            $offer->currency = Core::getCurrencyByType($offer->currency)->currency;
        }

        Json::filter($offers, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/shop.html', ['permission' => 'housekeeping_shop_control', 'offers' => Admin::getOffers()]);
    }
}