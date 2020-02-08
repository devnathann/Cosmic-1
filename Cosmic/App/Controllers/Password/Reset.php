<?php
namespace App\Controllers\Password;

use App\Models\Log;
use App\Models\Password;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\Json;

use stdClass;

class Reset
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function validate()
    {
        $validate = request()->validator->validate([
            'new_password'      => 'required|min:6|max:32',
            'repeated_password' => 'required|min:6|max:32|same:new_password',
            'token'             => 'max:150'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $token = input()->post('token')->value;
        $newPassword = input()->post('new_password')->value;

        $player = Password::getByToken($token, true);
        if ($player == null || $player->timestamp < time()) {
            if($player->timestamp < time()) {
                Password::deleteToken($player->email);
            }

            response()->json(["status" => "error", "message" => Locale::get('claim/invalid_link'), "pagetime" => "/home"]);
        }

        Player::update($player->player_id, ['pincode' => null]);
        Player::resetPassword($player->player_id, $newPassword);
        Password::deleteToken($player->email);

        response()->json(["status" => "success", "message" => Locale::get('claim/password_changed'), "pagetime" => "/home"]);
    }

    public function index($token)
    {
        $player = Password::getByToken($token, true);
        if ($player == null) {
            redirect('/');
        }

        View::renderTemplate('Password/reset.html', [
            'title' => Locale::get('core/title/password/reset'),
            'page'  => 'password_reset',
            'data'  => $this->data,
            'token' => $token
        ]);

        return false;
    }
}
