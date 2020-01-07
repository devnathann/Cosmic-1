<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Models\Admin;
use App\Models\Log;
use App\Models\Player;
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
            'lang'      => 'required',
            'offer_id'  => 'required',
            'private_key' => 'required'
        ]);
      
        $id = input()->post('shopId')->value;
        $currencys = input()->post('currencys')->value;
        $amount = input()->post('amount')->value;
        $price = input()->post('price')->value;
        $lang = input()->post('lang')->value;
        $offer_id = input()->post('offer_id')->value;
        $private_key = input()->post('private_key')->value;
      
        if(!$validate->isSuccess()) {
            exit;
        }
      
        if (!empty($id)) {
            Admin::offerEdit($id, $currencys, $amount, $price, $lang, $offer_id, $private_key);
            Log::addStaffLog('-1', 'Shop edited: ' . $offer_id, 'shop');
            echo '{"status":"success","message":"Shop edited successfully!"}';
            exit;
        }
      
        Admin::offerCreate($currencys, $amount, $price, $lang, $offer_id, $private_key);
        Log::addStaffLog('-1', 'Shop item created: ' . $offer_id, 'shop');
        echo '{"status":"success","message":"Shop edited successfully!"}';
    }
  
    public function give()
    {
        $validate = request()->validator->validate([
            'username'      => 'required|max:15|pattern:[^[:space:]]+',
            'type'          => 'required'
        ]);

        $username = input()->post('username')->value;
        $type = input()->post('type')->value;

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataByUsername($username, array('id', 'online'));
        if (empty($player)) {
            echo '{"status":"success","message":"This user does not exists!"}';
            exit;
        }

        $offer = \App\Models\Shop::getOfferById($type);
        if (empty($offer)) {
            echo '{"status":"success","message":"This user does not exists!"}';
            exit;
        }

        if ($player->online) {
            HotelApi::execute('givepoints', array('user_id' => $player->id, 'points' => $offer->amount, 'type' => Config::currencys[$offer->currency]));
        } else {
            Player::updateCurrency($player->id, Config::currencys[$offer->currency], +$offer->amount);
        }

        Log::addPurchaseLog($player->id, $offer->amount . ' Bel-Credits', $offer->lang);
        echo '{"status":"success","message":"User has received the items!"}';
    }
  
    public function getOfferById()
    {
       $validate = request()->validator->validate([
            'post'        => 'required'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        $offer = Shops::getOfferById(input()->post('post')->value);
        Json::raw($offer);
    }

    public function getOffers()
    {
        $offers = Admin::getOffers();
        Json::filter($offers, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/shop.html', ['permission' => 'housekeeping_shop_control', 'offers' => Admin::getOffers(), 'currency' => Config::currencys]);
    }
}