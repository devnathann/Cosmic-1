<?php
namespace App\Controllers\Settings;

use App\Config;
use App\Core;

use App\Models\Core as Settings;
use App\Models\Ban;
use App\Models\Player;
use App\Models\Room;
use App\Models\Log;

use Core\Locale;
use Core\View;

use Library\HotelApi;
use Library\Json;

use stdClass;

class Namechange
{
    private $settings;

    public function __construct()
    {
        $this->settings = Settings::settings();
    }

    public function validate()
    {
        $validate = request()->validator->validate([
            'username' => 'required|min:2|max:15|pattern:[^[:space:]]+',
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $username = input()->post('username')->value;

        $user_validate = preg_replace('/[^a-zA-Z0-9\d\-\?!@:\.,]/i', '', $username);
        if ($user_validate != $username) {
            response()->json(["status" => "error", "message" => Locale::get('register/username_invalid')]);
        }

        $new_player = Player::getDataByUsername($username);
        if (!empty($new_player)) {
            response()->json(["status" => "error", "message" => Locale::get('settings/user_is_active')]);
        }

        $amount = Player::getCurrencys(request()->player->id)[$this->settings->namechange_currency_type]->amount;
        if ($amount < $this->settings->namechange_price) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/not_enough_points')]);
        }
      
        HotelApi::execute('changeusername', array('user_id' => request()->player->id, 'new_name' => $username));
        HotelApi::execute('givepoints', array('user_id' => request()->player->id, 'points' => - $amount + $amount - $this->settings->namechange_price, 'type' => $this->settings->namechange_currency_type));
      
        response()->json(["status" => "success", "message" => Locale::get('settings/name_change_saved'), "replacepage" => "settings/namechange"]);
    }

    public function availability()
    {
        $username = input()->post('username')->value;

        $userCheck = preg_replace('/[^a-zA-Z0-9\d\-\?!@:\.,]/i', '', $username);
        $player = Player::getDataByUsername($username, array('id'));

        if ($userCheck != $username || !empty($player)) {
            response()->json(["status" => "unavailable"]);
        }
    
        response()->json(["status" => "available"]);
    }

    public function index()
    {
        $currency = Settings::getCurrencyByType($this->settings->namechange_currency_type)->currency;
      
        View::renderTemplate('Settings/namechange.html', [
            'title' => Locale::get('core/title/settings/namechange'),
            'page'  => 'settings_namechange',
            'currency' => $currency,
            'price' => $this->settings->namechange_price
        ]);
    }
}