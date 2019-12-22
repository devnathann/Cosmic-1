<?php
namespace App\Controllers\Settings;

use App\Config;
use App\Core;

use App\Models\Ban;
use App\Models\Player;
use App\Models\Room;
use App\Models\Log;

use Core\Locale;
use Core\View;

use Library\HotelApi;

use stdClass;

class Namechange
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function validate()
    {
        if(!Config::apiEnabled) {
            echo '{"status":"error","message":"' . Locale::get('core/hotelapi/disable') . '"}';
            exit;
        }
      
        $validate = request()->validator->validate([
            'username' => 'required|min:2|max:15|pattern:[^[:space:]]+',
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $username = input()->post('username')->value;

        $user_validate = preg_replace('/[^a-zA-Z0-9\d\-\?!@:\.,]/i', '', $username);
        if ($user_validate != $username) {
            echo '{"status":"error","message":"' . Locale::get('register/username_invalid') . '"}';
            exit;
        }

        $new_player = Player::getDataByUsername($username);
        if (!empty($new_player)) {
            echo '{"status":"error","message":"' . Locale::get('settings/user_is_active') . '"}';
            exit;
        }

        $amount = Player::getCurrencys(request()->player->id)[Config::payCurrency]->amount;
        if ($amount < 50) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/not_enough_belcredits') . '"}';
            exit;
        }

        Player::updateCurrency(request()->player->id, Config::payCurrency, $amount - 50);
      
        foreach(Room::getByPlayerId(request()->player->id) as $room) {
            HotelApi::execute('changeroomowner', array('room_id' => $room->id, 'user_id' => request()->player->id, 'username' => $username));
        }
      
        if (request()->player->online) {
            HotelApi::execute('disconnect', array('user_id' => request()->player->id));
        }
      
        Log::addNamechangeLog(request()->player->id, request()->player->username, $username);
        Player::update(request()->player->id, 'username', $username);
        echo '{"status":"success","message":"' . Locale::get('settings/name_change_saved') . '","replacepage":"settings/namechange"}';
    }

    public function availability()
    {
        $username = input()->post('username')->value;

        $userCheck = preg_replace('/[^a-zA-Z0-9\d\-\?!@:\.,]/i', '', $username);
        $player = Player::getDataByUsername($username, array('id'));

        if ($userCheck != $username || !empty($player)) {
            echo '{"status":"unavailable"}';
            exit;
        }

        echo '{"status":"available"}';
    }

    public function index()
    {
        $currency = array_flip(Config::currencys);

        View::renderTemplate('Settings/namechange.html', [
            'title' => Locale::get('core/title/settings/namechange'),
            'page'  => 'settings_namechange',
            'currency' => $currency[Config::payCurrency],
            'price' => 50
        ]);
    }
}