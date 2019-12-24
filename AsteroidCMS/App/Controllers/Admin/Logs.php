<?php
namespace App\Controllers\Admin;

use App\Core;
use App\Models\Admin;

use App\Models\Player;
use Library\Json;

use Core\View;

class Logs
{
    public function getbanlogs()
    {
        $ban_logs = Admin::getAllBans();
        if ($ban_logs == null) {
            exit;
        }

        foreach ($ban_logs as $row) {
            $row->user_id = Player::getDataById($row->user_id, 'username')->username;
            $row->user_staff_id = Player::getDataById($row->user_staff_id, 'username')->username;
            $row->ban_expire = date("d-M-Y H:i:s", $row->ban_expire);
        }

        Json::filter($ban_logs, 'desc', 'id');
    }

    public function getchatlogs()
    {
        $chat_logs = Admin::getAllLogs(1000);

        foreach ($chat_logs as $row) {
            $row->user_from_id = Player::getDataById($row->user_from_id, 'username')->username;
            $row->timestamp = date("d-M-Y H:i:s", $row->timestamp);
        }

        Json::filter($chat_logs, 'desc', 'id');
    }

    public function getstafflogs()
    {
        $staff_logs = Admin::getStaffLogs(1000);

        foreach ($staff_logs as $row) {
            $row->username = Player::getDataById($row->player_id, 'username')->username ?? null;
            $row->timestamp = date("d-M-Y H:i:s", $row->timestamp);

            if (is_numeric($row->target)) {
                $row->target = Player::getDataById($row->target, 'username')->username ?? null;
            }
        }

        Json::filter($staff_logs, 'desc', 'id');
    }

    public function banlogs()
    {
        View::renderTemplate('Admin/Tools/banlogs.html', [
            'permission' => 'housekeeping_ban_logs',
        ]);
    }

    public function chatlogs()
    {
        View::renderTemplate('Admin/Tools/chatlogs.html', [
            'permission' => 'housekeeping_chat_logs',
        ]);
    }

    public function stafflogs()
    {
        View::renderTemplate('Admin/Management/stafflogs.html', [
            'permission' => 'housekeeping_staff_logs',
        ]);
    }
}