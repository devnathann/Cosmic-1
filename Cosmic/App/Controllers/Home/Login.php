<?php
namespace App\Controllers\Home;

use App\Auth;
use App\Config;
use App\Core;
use App\Hash;

use App\Models\Player;

use Core\Locale;
use Core\Session;

use Core\View;

use Library\Json;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Login
{
    private $auth;

    public function logout()
    {
        Auth::logout();
        redirect('/');
    }

    public function request()
    {
        $validate = request()->validator->validate([
            'username' => 'required|min:1|max:30',
            'password' => 'required|min:1|max:100',
            'pincode'  => 'max:6'
        ]);

        if (!$validate->isSuccess()) {
            return;
        }

        $username     = input()->post('username')->value;
        $password     = input()->post('password')->value;
        $remember_me  = input()->post('remember_me')->value;
        $pin_code     = !empty(input()->post('pincode')->value) ? input()->post('pincode')->value : false;

        $player = Player::getDataByUsername($username, array('id', 'password', 'rank', 'secret_key'));
        if ($player == null || !Hash::verify($password, $player->password)) {
            return Json::encode(["status" => "error", "message" => Locale::get('login/invalid_password')]);
        }

        /*
        *  Verification authentication
        */

        if(!$pin_code) {
            if (!is_null($player->secret_key)) {
                return Json::encode(["status" => "pincode_required"]);
            }
        }

        if ($pin_code && $player->secret_key == null) {
            return Json::encode(["status" => "error", "message" => Locale::get('login/invalid_pincode')]);
        }

        if($player->secret_key != null) {
            $this->googleAuthentication($pin_code, $player->secret_key);
        }

        /*
        *  End authentication
        */

        $this->login($player, $remember_me);
    }

    protected function login($user, $remember_me)
    {
        if ($user && Auth::login($user, $remember_me)) {
            return Json::encode(["status" => "error", "location" => "/home"]);
        } else {
            return Json::encode(["status" => "error", "message" => Locale::get('login/invalid_password')]);
        }
    }

    protected function googleAuthentication($pin_code, $secret_key)
    {
        $this->auth = new GoogleAuthenticator();

        if (!$this->auth->checkCode($secret_key, $pin_code)) {
            return Json::encode(["status" => "error", "message" => Locale::get('login/invalid_pincode')]);
        }

        return true;
    }
}
