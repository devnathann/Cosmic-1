<?php
namespace App\Models;

use App\Token;

use QueryBuilder;
use PDO;

class RememberedLogin 
{
    public static function findByToken($token)
    {
        $token = new Token($token);
        $token_hash = $token->getHash();

        return QueryBuilder::table('website_remembered_logins')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('token_hash', $token_hash)->first();
    }

    public function getPlayer()
    {
        return Player::getDataById($this->user_id);
    }

    public function hasExpired()
    {
        return strtotime($this->expires_at) < time();
    }

    public function delete()
    {
        return QueryBuilder::table('website_remembered_logins')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('token_hash', $this->token_hash)->delete();
    }
}
