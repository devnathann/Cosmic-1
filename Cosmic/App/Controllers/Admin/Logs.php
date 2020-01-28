<?php
namespace App\Controllers\Admin;

use App\Core;
use App\Config;
use App\Models\Admin;
use App\Models\Log;

use App\Models\Player;
use Library\Json;

use Core\View;

class Logs
{
    public function __construct() {
        $this->data = new \stdClass();
    }
  
    public function getCompareUsersLogs()
    {
        $validate = request()->validator->validate([
            'element'   => 'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $this->data->users = input()->post('element');

        foreach ($this->data->users as $row) {
            $player[] = Player::getDataByUsername($row, 'id')->id;
        }

        $players = join(',', array_map('intval', $player));
        $this->data->chatlogsall = Admin::getCompareLogs("{$players}");

        foreach ($this->data->chatlogsall as $logs) {
          
            $player = Player::getDataById($logs->user_from_id);

            if($player->rank >= request()->player->rank) {
                Log::addStaffLog($player->id, 'Manage Multiple Chatlogs (No permission)', request()->player->id, 'check');
                exit;
            }

            $logs->name       = 'MESSAGE';
            $logs->player     = $player->username;
            $logs->timestamp  = date("d-m-Y H:i:s", $logs->timestamp);
            $logs->message    = Core::filterString($logs->message);
          
            $this->username[$player->id] = $player->id;
          
            if($logs->user_to_id != 0) {
                $logs->name     = 'WHISPER';
                $logs->message  = Core::filterString('<b>' . Player::getDataById($logs->user_to_id, array('username'))->username . '</b>: ' . $logs->message);
            }
        }

        foreach ($this->data->users as $row) {
            Log::addStaffLog(Player::getDataByUsername($row, 'id')->id, 'Manage Multiple Chatlogs', request()->player->id, 'check');
        }

        Json::encode($this->data->chatlogsall);
    }

  
    public function getbanlogs()
    {
        $ban_logs = Admin::getAllBans();
        if ($ban_logs == null) {
            exit;
        }

        foreach ($ban_logs as $row) {
            $row->user_id         = Player::getDataById($row->user_id, 'username')->username;
            $row->user_staff_id   = Player::getDataById($row->user_staff_id, 'username')->username;
            $row->ban_expire      = date("d-M-Y H:i:s", $row->ban_expire);
        }

        Json::filter($ban_logs, 'desc', 'id');
    }

    public function getchatlogs()
    {
        $chat_logs = Admin::getAllLogs(1000);
        foreach ($chat_logs as $logs) 
        {
            $logs->timestamp    = date("d-m-Y H:i:s", $logs->timestamp);
            $logs->message      = Core::filterString($logs->message);
            $logs->user_from_id = Player::getDataById($logs->user_from_id, array('username'))->username;
          
            if($logs->user_to_id != 0) {
                $logs->message  = Core::filterString('<b>' . Player::getDataById($logs->user_to_id, array('username'))->username . '</b>: ' . $logs->message);
            }
        }
    
        Json::encode($chat_logs);
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