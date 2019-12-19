<?php
namespace App;

use App\Models\Admin;
use App\Models\Ban;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Player;

use Core\Locale;
use Core\Session;

class Auth
{
    public static function login(Player $player)
    {
        $ban = Ban::getBanById($player->id, Core::getIpAddress());
        if($ban) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/banned_1').' ' . $ban->ban_reason . '. '.Locale::get('core/notification/banned_2').' ' . Core::timediff($ban->ban_expire, true) . '"}';
            exit;
        }
                                          
        Session::set('player_id', $player->id);
        Session::set('ip_address', Core::getIpAddress());

        if (in_array('housekeeping', array_column(Permission::get($player->rank), 'permission'))) {
            Log::addStaffLog('-1', 'Staff logged in: '.Core::getIpAddress(), 'LOGIN');
        }

        Player::update($player->id, 'ip_current', Core::getIpAddress());
        Player::update($player->id, 'last_online', time());

        return $player;
    }
  
    public static function logout()
    {
        Session::destroy();
    }

    public static function maintenance()
    {
        return \App\Models\Core::getWebsiteConfig('maintenance') ? true : false;
    }
}