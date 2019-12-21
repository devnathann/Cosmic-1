<?php
namespace App\Controllers\Shop;

use App\Config;

use App\Models\Log;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\HotelApi;

class Club
{
    public function index()
    {
        $this->data = new \stdClass();
      
        $this->data->price = Config::vipPrice;
        $this->data->vip = Player::getDataByRank(Config::vipRank, 5);

        foreach(Config::currencys as $value => $key) {
            if($key == Config::payCurrency) 
                $this->data->type = $value;
        }

        View::renderTemplate('Shop/club.html', [
            'title' => Locale::get('core/title/shop/club'),
            'page'  => 'shop_club',
            'data'  => $this->data
        ]);
    }

    public function buy() {
        $currency = Player::getCurrencys(request()->player->id)[Config::payCurrency];
        if(!$currency) {
            echo '{"status":"error","message":"Je moet eerst ingelogd zijn binnen het hotel om dit te kunnen kopen!"}';
            exit;
        }
      
        if($currency->amount < Config::vipPrice) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/not_enough_belcredits').'"}';
            exit;
        }
      
        if(request()->player->rank >= Config::vipRank ||request()->player->rank == Config::vipRank) {
            echo '{"status":"error","message":"'.Locale::get('shop/club/already_vip').'"}';
            exit;
        }

        $playerCurrency = Player::getCurrencys(request()->player->id)[Config::payCurrency];
      
        if(request()->player->online && Config::apiEnabled) {
            HotelApi::execute('givepoints', array('user_id' => request()->player->id, 'points' => -Config::vipPrice, 'type' => Config::payCurrency));
            HotelApi::execute('givebadge', array('user_id' => request()->player->id, 'badge' => "VIP"));
            HotelApi::execute('givebadge', array('user_id' => request()->player->id, 'badge' => "HC1"));
            HotelApi::execute('givebadge', array('user_id' => request()->player->id, 'badge' => "DON1"));
            HotelApi::execute('givebadge', array('user_id' => request()->player->id, 'badge' => "DON2"));
            HotelApi::execute('givebadge', array('user_id' => request()->player->id, 'badge' => "DON3"));
        } else {
            Player::updateCurrency(request()->player->id, Config::payCurrency, $playerCurrency->amount - Config::vipPrice);
            Player::giveBadge(request()->player->id, "VIP");
            Player::giveBadge(request()->player->id, "HC1");
            Player::giveBadge(request()->player->id, "DON1");
            Player::giveBadge(request()->player->id, "DON2");
            Player::giveBadge(request()->player->id, "DON3");
        }
      
        HotelApi::execute('setrank', array('user_id' => request()->player->id, 'rank' => Config::vipRank));
        Log::addPurchaseLog(request()->player->id, Config::shortName.' Club ('.Config::vipPrice.' '.$playerCurrency->name.')', 'NL');

        echo '{"status":"success","message":"'.Locale::get('shop/club/purchase_success').'", "replacepage":"shop/club"}';
        exit;
    }
}