<?php
namespace App\Models;

use App\Core;

use Core\Session;

use PDO;
use QueryBuilder;

class Log
{
    public static function createEmailLog($player_id, $new_email, $old_email)
    {
        $data = array(
            'player_id' => $player_id,
            'ip_address' => request()->getIp(),
            'new_mail' => $new_email,
            'old_mail' => $old_email,
            'timestamp' => time()
        );

        return QueryBuilder::table('player_logs_email')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function addStaffLog($target_id, $value, $player_id, $type)
    {
        $data = array(
            'player_id' => $player_id,
            'type'      => $type,
            'value'     => $value,
            'timestamp'      => time(),
            'target'    => $target_id
        );

        return QueryBuilder::table('website_staff_logs')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }
  
    public static function addNamechangeLog($user_id, $old_name, $new_name)
    {
        $data = array(
            'user_id' => $user_id,
            'old_name'      => $old_name,
            'new_name'     => $new_name,
            'timestamp'      => time()
        );

        return QueryBuilder::table('namechange_log')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function addHelpTicketLog($player_id, $type, $target, $value)
    {
        $data = array(
            'player_id' => $player_id,
            'target'    => $type,
            'value'     => $value,
            'type'      => $target,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_helptool_logs')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function addPurchaseLog(int $player_id, String $data, $language)
    {
        $data = array(
            'user_id' => $player_id,
            'data'      => $data,
            'lang'      => $language,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_shop_purchases')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }
}