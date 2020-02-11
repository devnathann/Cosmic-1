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
use Library\HotelApi;

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
            exit;
        }

        $username     = input()->post('username')->value;
        $password     = input()->post('password')->value;
        $remember_me  = input()->post('remember_me')->value ?? false;
      
        $pin_code     = !empty(input()->post('pincode')->value) ? input()->post('pincode')->value : false;

        $player = Player::getDataByUsername($username, array('id', 'password', 'rank', 'secret_key', 'pincode'));
        if ($player == null || !Hash::verify($password, $player->password)) {
            response()->json(["status" => "error", "message" => Locale::get('login/invalid_password')]);
        }

        /*
        *  Verification authentication
        */

        if(!$pin_code) {
            if (!empty($player->secret_key) || !empty($player->pincode)) {
                response()->json(["status" => "pincode_required"]);
            }
        }

        if ($pin_code && empty($player->secret_key) && empty($player->pincode)) {
            response()->json(["status" => "error", "message" => Locale::get('login/invalid_pincode')]);
        }
      
        if(!empty($player->pincode) && empty($player->secret_key)) {
            if($player->pincode !== $pin_code) {
                response()->json(["status" => "error", "message" => Locale::get('login/invalid_pincode')]);
            }
        }

        if(!empty($player->secret_key) && empty($player->pincode)) {
            $this->googleAuthentication($pin_code, $player->secret_key);
        }

        /*
        *  End authentication
        */

        $this->login($player, $remember_me);
    }

    protected function login(Player $user, $remember_me)
    {
        if ($user && Auth::login($user, $remember_me)) {
            response()->json(["status" => "error", "location" => "/home"]);
        } else {
            response()->json(["status" => "error", "message" => Locale::get('login/invalid_password')]);
        }
    }

    protected function googleAuthentication($pin_code, $secret_key)
    {
        $this->auth = new GoogleAuthenticator();

        if (!$this->auth->checkCode($secret_key, $pin_code)) {
            response()->json(["status" => "error", "message" => Locale::get('login/invalid_pincode')]);
        }

        return true;
    }
}
