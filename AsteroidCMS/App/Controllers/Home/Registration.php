<?php
namespace App\Controllers\Home;

use App\Config;
use App\Auth;
use App\Models\Player;

use Core\Locale;
use Core\View;

class Registration
{
    public function request()
    {
        $validate = (request())->validator->validate([
            'username'              => 'required|min:2|max:15|pattern:[a-zA-Z0-9-=?!@:.]+',
            'email'                 => 'required|max:72|email',
            'password'              => 'required|min:6|max:32',
            'password_repeat'       => 'required|same:password',
            'birthdate_day'         => 'required|numeric|pattern:0?[1-9]OR[12][0-9]OR3[01]',
            'birthdate_month'       => 'required|numeric',
            'birthdate_year'        => 'required|numeric',
            'gender'                => 'required|pattern:^(?:maleORfemale)$',
            'figure'                => 'required|figure',
            'g-recaptcha-response'  => 'required|captcha'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $username = input()->post('username')->value;

        $playerData = (object)input()->all();
        $playerData->figure = input()->post('figure')->value;

        if (Player::exists($username)) {
            echo '{"status":"error","message":"' . Locale::get('register/username_exists') . '"}';
            exit;
        }

        if (!Player::create($playerData)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '","captcha_error":"error"}';
            exit;
        }
      
        $player = Player::getDataByUsername($username, array('id', 'password', 'rank'));
      
        if(Config::currencys) {
            foreach(Config::currencys as $column => $type) {
                Player::createCurrency($player->id, $type);
                Player::updateCurrency($player->id, $type, Config::freeCurrency[$column]);
            }
        }
      
        Auth::login($player);

        echo '{"status":"success","message":"","location":"/hotel"}';
    }

    public function index()
    {
        View::renderTemplate('Home/registration.html', [
            'title' => Locale::get('core/title/registration'),
            'page'  => 'registration'
        ]);
    }
}