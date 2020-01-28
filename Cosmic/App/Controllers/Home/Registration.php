<?php
namespace App\Controllers\Home;

use App\Config;
use App\Auth;
use App\Core;

use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\Json;

class Registration
{
    public function request()
    {
        $validate = request()->validator->validate([
            'username'              => 'required|min:2|max:15|pattern:[a-zA-Z0-9-=?!@:.]+',
            'email'                 => 'required|max:150|email',
            'password'              => 'required|min:6|max:32',
            'password_repeat'       => 'required|same:password',
            'birthdate_day'         => 'required|numeric|pattern:0?[1-9]OR[12][0-9]OR3[01]',
            'birthdate_month'       => 'required|numeric',
            'birthdate_year'        => 'required|numeric',
            'gender'                => 'required|pattern:^(?:maleORfemale)$',
            'figure'                => 'required|figure',
            'g-recaptcha-response'  => 'captcha'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
      
        $username = input()->post('username')->value;

        $playerData = (object)input()->all();
        $playerData->figure = input()->post('figure')->value;

        if (Player::exists($username)) {
            return Json::encode(["status" => "error", "message" => Locale::get('register/username_exists')]);
        }

        if (Player::mailTaken(input()->post('email')->value)) {
            return Json::encode(["status" => "error", "message" => Locale::get('register/email_exists')]);
        }
      
        if (Player::checkMaxIp(Core::getIpAddress()) >= \App\Models\Core::settings()->registration_max_ip) {
            return Json::encode(["status" => "error", "message" => Locale::get('register/too_many_accounts')]);
        }

        if (!Player::create($playerData)) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/something_wrong'), "captcha_error" => "error"]);
        }
  
        $player = Player::getDataByUsername($username, array('id', 'password', 'rank'));
      
        $freeCurrencys = \App\Models\Core::getCurrencys();
      
        if($freeCurrencys) {
            foreach($freeCurrencys as $currency) {
                Player::createCurrency($player->id, $currency->type);
                Player::updateCurrency($player->id, $currency->type, $currency->amount);
            }
        }

        Auth::login($player);
        return Json::encode(["status" => "success", "location" => "/hotel"]);
    }

    public function index()
    {
        View::renderTemplate('Home/registration.html', [
            'title' => Locale::get('core/title/registration'),
            'looks' => Config::look,
            'page'  => 'registration'
        ]);
    }
}
