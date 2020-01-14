<?php
namespace App\Controllers\Settings;

use App\Hash;
use App\Models\Player;

use Core\Locale;
use Core\Session;
use Core\View;

use Library\Json;

use stdClass;

class Password
{
    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function request()
    {
        $validate = request()->validator->validate([
            'current_password'  => 'required|min:6',
            'new_password'      => 'required|min:6|max:32',
            'repeated_password' => 'required|same:new_password'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $currentPassword = input()->post('current_password')->value;
        $this->data->newpin = input()->post('new_password')->value;

        if (!Hash::verify($currentPassword, request()->player->password)) {
            return Json::encode(["status" => "error", "message" => Locale::get('settings/current_password_invalid')]);
        }
        Player::resetPassword(request()->player->id, $this->data->newpin);
        Session::destroy();

        return Json::encode(["status" => "success", "message" => Locale::get('settings/password_saved'), "pagetime" => "/home"]);
    }

    public function index()
    {
        View::renderTemplate('Settings/password.html', [
            'title' => Locale::get('core/title/settings/password'),
            'page'  => 'settings_password',
            'data'  => $this->data
        ]);
    }
}
