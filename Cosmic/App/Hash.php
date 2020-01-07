<?php
namespace App;

class Hash
{
    public static function password($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify($player_id, $password, $hash)
    {
        return password_verify($password, $hash) ? true : false;
    }
}
