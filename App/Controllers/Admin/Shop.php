<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Models\Admin;
use App\Models\Log;
use App\Models\Player;

use Core\View;

use Library\HotelApi;
use Library\Json;

class Shop
{
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

        $player = Player::getDataByUsername($username, array('id', 'belcredits', 'online'));
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
            HotelApi::player($player->id, 'reload');
        }

        Player::update($player->id, 'belcredits', $player->belcredits + $offer->amount);
        Log::addPurchaseLog($player->id, $offer->amount . ' Bel-Credits', $offer->lang);
        HotelApi::achievement($player->id, 'bought');
        echo '{"status":"success","message":"User has received the items!"}';
    }

    public function getPurchaseLogs()
    {
        $purchaseLogs = Admin::getPurchaseLogs();
        foreach ($purchaseLogs as $row) {
            $row->player_id = Player::getDataById($row->player_id, 'username')->username;
            $row->timestamp = date('d-M-Y h:i', $row->timestamp);
        }

        Json::filter($purchaseLogs, 'desc', 'id');
    }

    public function view()
    {
        $offers = Admin::getOffers();
        if ($offers == null) {
            exit;
        }

        View::renderTemplate('Admin/Management/shop.html', ['permission' => 'housekeeping_shop_control', 'offers' => $offers]);
    }
}