<?php
namespace App\Models;

use App\Config;
use App\Core;
use App\Hash;

use QueryBuilder;
use PDO;

class Player
{
    private static $data = array('id','username','password','real_name','mail','account_created','account_day_of_birth','last_login','online','pincode','last_online','motto','look','gender','rank','credits','pixels','points','auth_ticket','ip_register','ip_current','machine_id', 'secret_key');

    public static function getDataById($player_id, $data = null)
    {
        return QueryBuilder::table('users')->select($data ?? static::$data)->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $player_id)->first();
    }

    public static function getDataByUsername($username, $data = null)
    {
        return QueryBuilder::table('users')->select($data ?? static::$data)->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('username', $username)->first();
    }

    public static function getDataByRank($rank, $limit = 10)
    {
        return  QueryBuilder::table('users')->select(array('id','username','online','look', 'motto'))->setFetchMode(PDO::FETCH_CLASS, get_called_class())
            ->where('rank', $rank)->orderBy('online', 'desc')->get();
    }

    public static function getUserCurrencys($user_id, $type)
    {
        return  QueryBuilder::table('users_currency')->where('user_id', $user_id)->where('type', $type)->first();
    }

    public static function exists($username)
    {
        return static::getDataByUsername($username) == null ? false : true;
    }

    public static function getSettings($player_id)
    {
        return QueryBuilder::table('users_settings')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($player_id, 'user_id');
    }

    public static function getByAchievement($limit = 10)
    {
        return  QueryBuilder::table('users_settings')->select('user_id')->select('achievement_score')->orderBy('achievement_score', 'desc')->limit($limit)->get();
    }

    public static function update($player_id, $key, $value){
        return QueryBuilder::table('users')->where('id', $player_id)->update(array($key => $value));
    }
  
    public static function updateCurrency($player_id, $type, $value){
        return QueryBuilder::table('users_currency')->where('user_id', $player_id)->where('type', $type)->update(array('amount' => $value));
    }

    public static function updateSettings($player_id, $column, $type){
        return QueryBuilder::table('player_settings')->where('player_id', $player_id)->update(array($column => "$type"));
    }

    public static function create($data)
    {
            $data = array(
                'username' => $data->username,
                'password' => Hash::password($data->password),
                'mail' => $data->email,
                'account_created' => time(),
                'credits' => Config::credits,
                'points' => Config::points,
                'pixels'  => Config::pixels,
                'look' => $data->figure,
                'account_day_of_birth' => strtotime($data->birthdate_day . '-' . $data->birthdate_month . '-' . $data->birthdate_year),
                'gender' => $data->gender == 'male' ? 'M' : 'F',
                'last_login' => time(),
                'ip_register' => Core::getIpAddress(),
                'ip_current' => Core::getIpAddress()
            );

            $user_id = QueryBuilder::table('users')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
            QueryBuilder::table('users_settings')->insert(array('user_id' => $user_id, 'home_room' => Config::homeRoom));

            return $user_id;
    }
  
    public static function createCurrencys($user_id, $type)
    {
        return QueryBuilder::table('users_currency')->insert(array('user_id' => $user_id, 'type' => $type, 'amount' => 0)); 
    }

    public static function resetPassword($player_id, $password)
    {
        $password_hash = Hash::password($password);

        $data = array(
            'password'  => $password_hash,
        );

        return QueryBuilder::table('players')->where('id', $player_id)->update($data);
    }

    /* Get queries */

    public static function getAccessLogs($player_id, $limit = 100)
    {
        return QueryBuilder::table('player_access')->where('player_id', $player_id)->limit($limit)->get();
    }

    public static function getBadges($user_id, $limit = 5)
    {
        return QueryBuilder::table('users_badges')->where('user_id', $user_id)->orderBy('slot_id', 'DESC')->limit($limit)->get();
    }

    public static function getFriends($user_id, $limit = 5)
    {
        return QueryBuilder::query('SELECT users.look, users.username FROM messenger_friendships JOIN users ON messenger_friendships.user_one_id = users.id WHERE user_two_id = "' . $user_id .'"  ORDER BY RAND() LIMIT  ' . $limit)->get();
    }

    public static function getOnlineFriends($player_id, $limit = 100)
    {
        return QueryBuilder::query('SELECT DISTINCT m.user_two_id, p.username, p.figure FROM messenger_friendships m LEFT JOIN players p ON p.id = m.user_two_id LEFT JOIN player_settings ps ON ps.player_id = p.id WHERE user_one_id = "'.$player_id.'" AND p.online = "1" AND ps.hide_online = "0" ORDER BY RAND() LIMIT  ' . $limit)->get();
    }

    public static function getGroups($user_id, $limit = 5)
    {
        return QueryBuilder::query('SELECT * FROM guilds WHERE user_id = "' . $user_id .'"  ORDER BY RAND() LIMIT  ' . $limit)->get();

    }

    public static function getRooms($player_id, $limit = 5)
    {
        return QueryBuilder::query('SELECT * FROM rooms WHERE owner_id = "' . $player_id .'" AND state != "INVISIBLE" ORDER BY RAND() LIMIT ' . $limit)->get();
    }

    public static function getPhotos($player_id, $limit = 5)
    {
        return QueryBuilder::query('SELECT * FROM camera_web WHERE user_id = "' . $player_id .'" ORDER BY RAND() LIMIT  ' . $limit)->get();
    }

    public static function getHotelRank($rank_id)
    {
        return QueryBuilder::table('permissions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($rank_id);
    }

    public static function getNamechangeRequest($playerid)
    {
        return QueryBuilder::table('website_namechange_requests')->where('status', 'open')->find($playerid, 'player_id');
    }
  
    public static function giveBadge($user_id, $badge)
    {
        $data = array(
            'user_id' => $user_id,
            'slot_id' => 0,
            'badge_code' => $badge
        );

        return QueryBuilder::table('users_badges')->insert($data);
    }

    public static function getPurchases($player_id)
    {
        return QueryBuilder::table('website_shop_purchases')->where('user_id', $player_id)->orderBy('id', 'desc')->get();
    }
    
    public static function getCurrencys($user_id)
    {
        $data = array();
        foreach(Config::currencys as $row => $colum) {
            $data[$colum] = self::getUserCurrencys($user_id, $colum);
          
            if(isset($data[$colum])) 
                $data[$colum]->name = $row;
               
        }
        return $data;
    }
}