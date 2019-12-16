<?php
namespace App\Models;

use PDO;
use QueryBuilder;

class Ban
{
    /* Bans */
    public static function getBans($user_id, $ip_address)
    {
        return QueryBuilder::table('bans')->where('timestamp', '<', time())->where('user_id', $user_id)->orWhere('ip', $ip_address)->first();
    }

    public static function getBanById($user_id, $ip_address)
    {
        return QueryBuilder::table('bans')->where('ban_expire', '>', time())->where('user_id', $user_id)->orWhere('ip', $ip_address)->first();
    }

    public static function insertBan($user_id, $ip_address, $staff_id, $expire, $reason, $type)
    {
        $data = array(
            'user_id' => $user_id,
            'ip' => $ip_address,
            'user_staff_id' => $staff_id,
            'timestamp' => time(),
            'ban_expire' => $expire,
            'ban_reason' => $reason,
            'type' => $type
        );
      
        $bans = self::getBans($user_id, $ip_address);
        if($bans) {
            return QueryBuilder::table('bans')->where('id', $bans->id)->update($data);
        }
      
        return QueryBuilder::table('bans')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    /* ASN bans */
    public static function getNetworkBans()
    {
        return QueryBuilder::table('website_bans_asn')->get();
    }

  
  
    public static function getNetworkBanById($id)
    {
        return QueryBuilder::table('website_bans_asn')->where('id', $id)->first();
    }

    public static function getNetworkBanByAsn($asn)
    {
        return QueryBuilder::table('website_bans_asn')->where('asn', $asn)->first();
    }


    public static function createNetworkBan($asn, $host, $added_by)
    {
        $data = array(
            'asn' => $asn,
            'host' => $host,
            'added_by' => $added_by,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_bans_asn')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function removeNetworkBan($id)
    {
        return QueryBuilder::table('website_bans_asn')->where('asn', $id)->delete();
    }

    /* Room bans */
    public static function getRoomBanById($id)
    {
        return QueryBuilder::table('room_bans')->where('id', $id)->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('id', 'desc')->first($id);
    }

    public static function getRoomBanByRoomId($room_id)
    {
        return QueryBuilder::table('room_bans')->where('room_id', $room_id)->setFetchMode(PDO::FETCH_CLASS, get_called_class())->get($room_id);
    }

    public static function deleteRoomBan($id)
    {
        return QueryBuilder::table('room_bans')->where('id', $id)->delete();
    }

}