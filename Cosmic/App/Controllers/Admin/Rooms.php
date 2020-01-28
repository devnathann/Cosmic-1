<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Ban;
use App\Models\Log;
use App\Models\Player;
use App\Models\Room;

use Core\View;

use Library\Json;

class Rooms
{
    public function update()
    {
        $validate = request()->validator->validate([
            'roomName'      => 'required|max:50',
            'roomDesc'      => 'max:50',
            'accessType'    => 'required|pattern:^(?:openORlockedORpasswordORinvisible)$',
            'maxUsers'      => 'required|max:4|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $room_id = Room::getById(input()->post('roomId')->value)->id;

        if(empty($room_id)) { 
            return Json::encode(["status" => "error", "message" => "This room does not exists!"]);
        }

        $room_name = input()->post('roomName')->value;
        $room_desc = input()->post('roomDesc')->value;
        $access_type = input()->post('accessType')->value;
        $max_users = input()->post('maxUsers')->value;

        Room::save($room_id, $room_name, $room_desc, $access_type, $max_users);
        Log::addStaffLog(request()->player->id, 'Saved room: ' . $room_name, request()->player->id, 'manage');

        return Json::encode(["status" => "success", "message" => "Room saved!"]);
    }

    public function delete()
    {
        $ban = Ban::getRoomBanById(input()->post('id')->value);
        if (empty($ban)) {
            return Json::encode(["status" => "error", "message" => "Ban doesnt exist!"]);
        }

        Ban::deleteRoomBan($ban->id);
        return Json::encode(["status" => "success", "message" => "Ban deleted!"]);
    }

    public function get()
    {
        $room = Room::getById(input()->post('post'));

        if (empty($room)) {
            return Json::encode(["status" => "error", "message" => "No results!"]);
        }

        $roomData = Room::getById($room->id);
        echo Json::encode($roomData);
    }

    public function getroombans()
    {
        $bans = Ban::getRoomBanByRoomId(input()->post('roomId')->value);
        foreach ($bans as $row) {
            $row->username = Player::getDataById($row->player_id, 'username')->username;
            $row->expire = date('d-m-Y H:i', $row->ends);
        }

        Json::filter($bans, 'desc', 'id');
    }

    public function getpopularrooms()
    {
        Json::filter(Admin::getPopularRooms(), 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Tools/rooms.html', ['permission' => 'housekeeping_room_control']);
    }
}