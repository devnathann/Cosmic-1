<?php
namespace App\Controllers\Home;

use App\Auth;

use App\Hash;
use App\Models\Player;

use Core\Locale;

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
        
        $username = input()->post('username')->value;
        $password = input()->post('password')->value;
        $pin_code =  !empty(input()->post('pincode')->value) ? input()->post('pincode')->value : false;
      
        $player = Player::getDataByUsername($username, array('id', 'password', 'rank', 'secret_key'));
        if ($player == null || !Hash::verify($player->id, $password, $player->password)) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_password') . '"}';
            exit;
        }

        /*
        *  Verification authentication
        */

        if(!$pin_code) {
            if (!is_null($player->secret_key)) {
                echo '{"status":"pincode_required"}';
                exit;
            }
        }
      
        if ($pin_code && $player->secret_key == null) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_pincode') . '"}';
            exit;
        }
        
        if($player->secret_key != null) {
            $this->googleAuthentication($pin_code, $player->secret_key);
        }
      
        /*
        *  End authentication
        */

        $this->login($player);
    }

    protected function login($user)
    {
        if ($user && Auth::login($user)) {
            echo '{"status":"success","message":"","location":"/home"}';
        } else {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_password') . '"}';
        }
    }

    protected function googleAuthentication($pin_code, $secret_key)
    {
        $this->auth = new GoogleAuthenticator();

        if (!$this->auth->checkCode($secret_key, $pin_code)) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_pincode') . '","close_popup":"loginpin"}';
            exit;
        }

        return true;
    }
}