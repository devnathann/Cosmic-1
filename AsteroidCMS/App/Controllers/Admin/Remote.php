<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Core;
use App\Flash;

use App\Models\Ban;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Player;
use App\Models\Admin;
use App\Models\PlayerStats;
use App\Models\Room;

use Core\View;

use Library\HotelApi;
use Library\Json;

use stdClass;

class Remote
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function user()
    {
        $username = explode("/", url()->getOriginalUrl())[5];
        if($username == null) {
            redirect('/');
            exit;
        }

        $this->user = Player::getDataByUsername($username);

        if (!isset($this->user)) {
            redirect('/housekeeping/remote/control');
        }

        $this->data->user               = (object)$this->user->username;

        $this->data->user->ip_current   = Core::convertIp($this->user->ip_current);
        $this->data->user->ip_register  = Core::convertIp($this->user->ip_register);
        $this->data->user->last_login   = $this->user->online ? 'Online' : date("d-m-Y H:i:s", $this->user->last_login);

        $this->data->user->id           = $this->user->id;
        $this->data->user->username     = $this->user->username;
        $this->data->user->rank_id      = $this->user->rank;
        $this->data->user->mail         = $this->user->mail;
        $this->data->user->motto        = $this->user->motto;
        $this->data->user->credits      = $this->user->credits;
      
        $this->data->user->currencys     = Player::getCurrencys($this->user->id);

        if(\App\Models\Core::permission('housekeeping_ranks', request()->player->rank)) {
            $this->data->hotel_ranks     = Permission::getRanks(true);
        }

        if ($this->user->rank >= request()->player->rank && $this->user->rank != Config::maxRank) {
            Log::addStaffLog($this->user->id, 'No permissions for Remote Control', 'error');
            Flash::addMessage('You have no permissions!', FLASH::ERROR);
            redirect('/housekeeping');
        }
      
        $log = isset($type) && !empty($type) ? $type : 'All user information';
        Log::addStaffLog($this->user->id, 'Checked ' . $log, 'check');

        $this->template();
    }

    public function template()
    {
        View::renderTemplate('Admin/Tools/remote.html', [
            'permission' => 'housekeeping_remote_control',
            'data' => $this->data
        ]);
    }

    public function view()
    {
        $this->data->alertmessages = Admin::getAlertMessages();
        $this->data->banmessages = Admin::getBanMessages();
        $this->data->bantime = Admin::getBanTime(request()->player->rank);

        View::renderTemplate('Admin/Tools/search.html', ['data' => $this->data, 'permission' => 'housekeeping_remote_control']);
        exit;
    }

    public function manage()
    {
        $validate = request()->validator->validate([
            'element'  => 'required|min:1'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataByUsername(input()->post('element')->value, 'username');
        $type = input()->post('type')->value;

        if ($player == null && $type != null) {
            echo '{"location":"/housekeeping/remote/user/view/' . $player->username . '/' . $type . '"}';
            exit;
        }

        echo '{"location":"/housekeeping/remote/user/view/' . $player->username . '"}';
    }

    public function reset()
    {
        $validate = request()->validator->validate([
            'element'   => 'required',
            'type'      => 'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataByUsername(input()->post('element')->value, array('id','username','online','gender'));
        if($player == null) {
            echo '{"status":"error","message":"User doesnt exist"}';
            exit;
        }

        if(!\App\Models\Core::permission('housekeeping_reset_user', request()->player->rank)) {
            Log::addStaffLog($player->id, 'No permissions to reset', 'error');
            echo '{"status":"error","message":"No permissions to reset"}';
            exit;
        }
  
        switch (input()->post('type')->value) {
            
            case 1:
            
                Player::update($player->id, 'motto', 'Onacceptabel voor het Hotel Management');
                Log::addStaffLog($player->id, 'Reset motto', 'reset');

                if (Config::apiEnabled && $player->online) {
                    HotelApi::execute('setmotto', array('user_id' => $player->id, 'motto' => 'Onacceptabel voor het Hotel Management'));
                }
            
                echo '{"status":"success","message":"The motto of ' . $player->username . ' is resetted!"}';

                break;

            case 2: 
                        
                Player::update($player->id, 'look', $player->gender == 'M' ? 
                    'hr-802-37.hd-185-1.ch-804-82.lg-280-73.sh-3068-1408-1408.wa-2001': 
                    'hr-890-35.hd-629-8.ch-665-76.lg-696-76.sh-730-64.ha-1003-64'
                );
            
                if (Config::apiEnabled && $player->online) {
                    HotelApi::execute('updateuser', array('user_id' => $player->id, 'look' => "hr-802-37.hd-185-1.ch-804-82.lg-280-73.sh-3068-1408-1408.wa-2001"));
                }
            
                echo '{"status":"success","message":"The look of ' . $player->username . ' is resetted!"}';

                break;
        }
    }

    public function alert()
    {
        $validate = request()->validator->validate([
            'element'   => 'required',
            'action'    => 'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataByUsername(input()->post('element')->value, array('id', 'username', 'online'));
        if($player == null) {
            echo '{"status":"error","message":"User doesnt exist"}';
            exit;
        }

        if(!\App\Models\Core::permission('housekeeping_alert_user', request()->player->rank)) {
            Log::addStaffLog($player->id, 'No permissions to send alert', 'error');
            echo '{"status":"error","message":"You have no permissions!"}';
            exit;
        }

        $alert_message = Admin::getAlertMessagesById(input()->post('reason')->value);

        if (!Config::apiEnabled || !$player->online) {
            echo '{"status":"error","message":"This user is offline."}';
            exit;
        }

        switch (input()->post('action')->value) {
            case 1:
                HotelApi::execute('disconnect', array('user_id' => $player->id));
                break;

            case 2:
                HotelApi::execute('muteuser', array('user_id' => $player->id, 'duration' => 600));
                break;
        }

        HotelApi::execute('alertuser', array('user_id' => $player->id, 'message' => $alert_message->message));
        Log::addStaffLog($player->id, 'Alert send: ' . $alert_message->message, 'alert');
        echo '{"status":"success","message":"The user ' . $player->username . ' received a alert."}';
          
    }

    public function ban()
    {
        $validate = request()->validator->validate([
            'element'   => 'required',
            'reason'    => 'required',
            'type'      => 'required',
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataByUsername(input()->post('element')->value, array('id','username','rank', 'ip_current'));
        if($player == null) {
            echo '{"status":"error","message":"The user does\'nt exists."}';
            exit;
        }

        if($player->rank >= Config::maxRank && \App\Models\Core::permission('housekeeping_ban_user', request()->player->rank)) {
            Log::addStaffLog($player->id, 'No permissions to ban', 'error');
            echo '{"status":"error","message":"You have no permissions to do this action!"}';
            exit;
        }
      
        $ban_message = Admin::getBanMessagesById(input()->post('reason')->value);
        $ban_time = Admin::getBanTimeById(input()->post('expire')->value);

        if(input()->post('type')->value == "ip") {
            Ban::insertBan($player->id, $player->ip_current, request()->player->id, time() + $ban_time->seconds, $ban_message->message, 'ip');
        } else {
            Ban::insertBan($player->id, $player->ip_current, request()->player->id, time() + $ban_time->seconds, $ban_message->message, 'account');
        }
        
        HotelApi::execute('disconnect', array('user_id' => $player->id));
        echo '{"status":"success","message":"The user ' . $player->username . ' is been banned: ' . $ban_time->message . '"}';
    }
  
    public function getplayer()
    {
        $player_id = input()->post('user_id')->value;

        $this->getChatLogs($player_id);
        $this->getUserLogs($player_id);
        $this->getClones($player_id);
        $this->getRoomLogs($player_id);
        $this->getTradeLogs($player_id);
        $this->getMailLogs($player_id);
        $this->getBanLogs($player_id);
        $this->getStaffLogs($player_id);

        if(request()->player->rank >= Config::maxRank) {
            $this->data->authorization = true;
        }

        Json::raw($this->data);
    }

    protected function getStaffLogs($player_id)
    {
        $this->data->stafflogs = Admin::getStaffLogsByPlayerId($player_id, 3000);

        if($this->data->stafflogs !== null)
          
            foreach ($this->data->stafflogs as $logs) {
              
                $logs->username = Player::getDataById($logs->player_id, 'username')->username;
                $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
              
                if (is_numeric($logs->target)) {
                    $logs->target = Player::getDataById($logs->target, 'username')->username ?? null;
                }
              
            }
    }

    protected function getMailLogs($player_id)
    {
        $this->data->maillogs = Admin::getMailLogs($player_id);
      
        foreach ($this->data->maillogs as $row) {
            $row->ip_address = Core::convertIp($row->ip_address);
            $row->timestamp = date("d-m-Y H:i:s", $row->timestamp);
        }
    }

    protected function getRoomLogs($player_id)
    {
        $this->data->rooms = Room::getByPlayerId($player_id);
    }

    protected function getTradeLogs($player_id)
    {
        $this->data->tradelogs = Admin::getTradeLogs($player_id);
        foreach($this->data->tradelogs as $item) {
          
            $item->user_one_id = Player::getDataById($item->user_one_id, array('username'));
            $item->user_two_id = Player::getDataById($item->user_two_id, array('username'));
          
            $item->items = Admin::getTradeLogItems($item->id);
          
            foreach($item->items as $trade) {
                $trade->user_id = Player::getDataById($trade->user_id, array('username'));
            }
          
            $item->timestamp = date("d-m-Y H:i:s", $item->timestamp);
        }
    }

    protected function getChatLogs($player_id)
    {
        $this->data->chatlogs = Admin::getChatLogs($player_id);
      
        foreach ($this->data->chatlogs as $logs) {
          
            $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
          
            if($logs->user_to_id != 0) {
                $logs->message = '<b>' . Player::getDataById($logs->user_to_id, array('username'))->username . '</b>: ' . $logs->message;
            }
        }
    }

    protected function getUserLogs($player_id)
    {
        $this->data->userlogs = Admin::getNameChangesById($player_id);
      
        foreach ($this->data->userlogs as $logs) {
            $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
        }
    }

    protected function getClones($player_id)
    {
        $userObject = Player::getDataById($player_id);
        $this->data->duplicateUsers = Admin::getClones($userObject->ip_current, $userObject->ip_register);
      
        foreach ($this->data->duplicateUsers as $row) {
            $row->iplast = Core::convertIp($row->ip_current);
            $row->ipreg = Core::convertIp($row->ip_register);
            $row->last_login = $row->online ? 'Online' : date("d-m-Y H:i:s", $row->last_login);
        }
    }

    protected function getMessengerLogs($player_id)
    {
        $this->data->messengerlogs = Admin::getMessengerLogs($player_id);
      
        foreach($this->data->messengerlogs as $row){
            $row->timestamp  = date("d-m-Y H:i:s", $row->timestamp);
        }
    }

    protected function getBanLogs($player_id) {
        $this->data->banlog = Admin::getBanLogByUserId($player_id);
      
        foreach($this->data->banlog as $ban) {
            $ban->user_staff_id = Player::getDataById($ban->user_staff_id, array('username'));
            $ban->ban_expire = date("d-m-Y H:i:s", $ban->ban_expire);
        }
    }

    public function unban($id)
    {
        $ban = \App\Models\Core::getField('bans', 'id', 'id', $id);

        if (empty($ban)) {
            echo '{"status":"error","message":"This player is does not exists!"}';
            exit;
        }

        if (!Admin::deleteBan($ban)) {
            echo '{"status":"error","message":"This player is not banned."}';
            exit;
        }

        HotelApi::reload('bans');
        echo '{"status":"error","message":"This player is unbanned."}';
    }

    public function change()
    {
        $validate = request()->validator->validate([
            'pincode'       => 'max:6|numeric',
            'motto'         => 'max:70'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $player = Player::getDataById(input()->post('user_id')->value);

        if(empty($player)) {
            echo '{"status":"error","message":"Player doesnt exist"}';
            exit;
        }

        $email = (input()->post('email')->value ? input()->post('email')->value : $player->mail);
        $pin_code = (input()->post('pincode')->value ? input()->post('pincode')->value : (string)$player->pincode);
        $motto = (input()->post('motto')->value ? input()->post('motto')->value : $player->motto);
        $rank = (input()->post('rank')->value ? input()->post('rank')->value : (string)$player->rank);
        $credits = (input()->post('credits')->value ? input()->post('credits')->value : (string)$player->credits);
      
        $currencys = Player::getCurrencys($player->id);
        foreach($currencys as $currency) {
            if($currency) {
                $currency->oldamount = $currency->amount;
                $currency->amount = (int)(input()->post($currency->name)->value ? input()->post($currency->name)->value : (string)$currency->amount);
            }
        }

        if(\App\Models\Core::permission('housekeeping_change_email', request()->player->rank)) {
            $validate = request()->validator->validate([
                'email' => 'required|min:6|max:72|email'
            ]);

            if(!$validate->isSuccess()) {
                exit;
            }
        }

        if(\App\Models\Core::permission('housekeeping_ranks',  request()->player->rank)) {
            $validate = request()->validator->validate([
                'rank' => 'required|numeric',
            ]);

            if(!$validate->isSuccess()) {
                exit;
            }
          
            foreach($currencys as $currency) {
                if($currency && !is_int($currency->amount)) {
                    echo '{"status":"success","message":"Currency must be numeric!"}';
                }
            }
        }
        
        if (Admin::changePlayerSettings($email ?? $player->mail, $motto, $credits, $pin_code, $player->id)) {

            if (Config::apiEnabled && $player->online) {
                if($player->rank != $rank) 
                    HotelApi::execute('setrank', array('user_id' => $player->id, 'rank' => $rank));
            } else {
                Player::update($player->id, 'rank', $rank);
            }

            foreach($currencys as $currency) {
                if($currency) {
                    if (Config::apiEnabled && $player->online && $currency->oldamount != $currency->amount) {
                        HotelApi::execute('givepoints', array('user_id' => $player->id, 'points' => -$currency->oldamount, 'type' => $currency->type));
                        HotelApi::execute('givepoints', array('user_id' => $player->id, 'points' => $currency->amount, 'type' => $currency->type));
                    } else {
                        Player::updateCurrency($player->id, $currency->type, $currency->amount);
                    }
                }
            }
          
            Log::addStaffLog($player->id, 'User Info saved', 'MANAGE');
            echo '{"status":"success","message":"Info of ' . $player->username . ' is updated!"}';
        }
    }
}
