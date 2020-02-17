<?php
namespace App\Models;

use App\Config;
use App\Mail;
use App\Token;

use Core\Locale;
use Core\View;

use PDO;
use QueryBuilder;

class Password
{
    public static function getByToken($token, $hash = false)
    {
        if($hash) {
            $token = new Token($token);
            $token = $token->getHash();
        }

        return QueryBuilder::table('website_password_reset')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('token', $token)->first();
    }

    public static function deleteToken($email)
    {
        return QueryBuilder::table('website_password_reset')->where('email', $email)->delete();
    }

    public static function createToken($player_id, $username, $email)
    {
        $token = new Token();
        $hashed_token = $token->getHash();

        $data = array(
            'player_id'     => $player_id,
            'email'         => $email,
            'ip_address'    => request()->getIp(),
            'token'         => $hashed_token,
            'timestamp'     => time() + 7200
        );

        QueryBuilder::table('website_password_reset')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);

        return self::sendMail($username, $email, $token->getValue());
    }

    public static function sendMail($username, $email, $token) {
        $url	= 'http://' . Config::site['domain'].'/password/reset/' . $token;
        $body 	= View::getTemplate('Password/body.html', ['url' => $url, 'username' => $username], true, true);
        return Mail::send(Locale::get('claim/email/title'), $body, $email);
    }
}