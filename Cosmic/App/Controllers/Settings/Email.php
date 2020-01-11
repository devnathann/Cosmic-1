<?php
namespace App\Controllers\Settings;

use App\Hash;

use App\Models\Log;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\Json;

class Email
{
    public function validate()
    {
        $validate = request()->validator->validate([
            'current_password' => 'required|max:100',
            'email'            => 'required|min:6|max:72|email'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $currentPassword = input()->post('current_password');
        $email = input()->post('email');

        if (!Hash::verify($currentPassword, request()->player->password)) {
            return Json::encode(["status" => "error", "message" => Locale::get('settings/current_password_invalid')]);
        }

        Player::update(request()->player->id, ['mail' => $email]);
        return Json::encode(["status" => "success", "message" => Locale::get('settings/email_saved'), "replacepage" => "settings/email"]);
    }

    public function index()
    {
        View::renderTemplate('Settings/email.html', [
            'title' => Locale::get('core/title/settings/email'),
            'page'  => 'settings_email'
        ]);
    }
}
