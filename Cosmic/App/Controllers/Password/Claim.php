<?php
namespace App\Controllers\Password;

use App\Models\Password;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\Json;

class Claim
{
    public function validate()
    {
        $validate = request()->validator->validate([
            'username'              => 'required|max:30',
            'email'                 => 'required|max:72|email',
            'g-recaptcha-response'  => 'captcha'
        ]);

        if (!$validate->isSuccess()) {
            return;
        }

        $username   = input()->post('username')->value;
        $email      = input()->post('email')->value;

        $player = Player::getDataByUsername($username, array('id', 'username', 'mail'));
        if ($player == null || strtolower($player->mail) != strtolower($email)) {
            response()->json(["status" => "error", "message" => Locale::get('claim/invalid_email'), "replacepage" => "password/claim"]);
        }

        Password::createToken($player->id, $player->username, $player->mail);
        response()->json(["status" => "success", "message" => Locale::get('claim/send_link'), "replacepage" => "password/claim"]);
    }

    public function index()
    {
        View::renderTemplate('Password/claim.html', [
            'title' => Locale::get('core/title/password/claim'),
            'page'  => 'password_claim'
        ]);
    }
}