<?php
namespace App\Controllers\Settings;

use App\Token;
use App\Hash;

use App\Models\Player;

use Core\Locale;
use Core\View;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Verification
{
    public function validate()
    {
        $this->auth = new GoogleAuthenticator();

        $validate = request()->validator->validate([
            'current_password'  => 'required|min:4'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        if (!Hash::verify(request()->player->id, input()->post('current_password')->value, request()->player->password)) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_password') . '"}';
            exit;
        }
      
        $verification_enabled = filter_var(input()->post('enabled')->value, FILTER_VALIDATE_BOOLEAN);
      
        /*
        *  Google Authentication
        */
      
        if(input()->post('type')->value == "app") {
        
            
            if (!$this->auth->checkCode(input()->post('data')->value, input()->post('input')->value)) {
                echo '{"status":"error","message":"' . Locale::get('settings/invalid_secretcode') . '"}';
                exit;
            }

            if($verification_enabled && request()->player->pincode == null) {
                Player::update(request()->player->id, 'secret_key', input()->post('data')->value);
                echo '{"status":"success","message":"' . Locale::get('settings/enabled_secretcode') . '","pagetime":"/logout"}';
                exit;
            }

            Player::update(request()->player->id, 'secret_key', NULL);
            echo '{"status":"success","message":"' . Locale::get('settings/disabled_secretcode') . '","replacepage":"settings/verification"}';
            exit;
        }
      
        /*
        *  Pincode Authentication
        */
        if(input()->post('type')->value == "pincode") {
      
            if($verification_enabled && request()->player->secret_key == NULL) {
                Player::update(request()->player->id, 'pincode', input()->post('data')->value);
                echo '{"status":"success","message":"' . Locale::get('settings/enabled_secretcode') . '","pagetime":"/logout"}';
                exit;
            }

            Player::update(request()->player->id, 'pincode', "0");
            echo '{"status":"success","message":"' . Locale::get('settings/disabled_secretcode') . '","replacepage":"settings/verification"}';
            exit;
        }
    }
  
    public function index()
    {
        View::renderTemplate('Settings/verification.html', [
            'title' => Locale::get('core/title/settings/index'),
            'page'  => 'settings_verification',
            'token' => (!request()->player->secret_key ? (new GoogleAuthenticator())->generateSecret() : request()->player->secret_key),
            'auth_enabled' => (request()->player->secret_key || (request()->player->pincode != NULL) ? true : false)
        ]);
    }
}