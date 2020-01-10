<?php
namespace App\Controllers\Ajax;

use App\Config;
use Core\Locale;

use Library\HotelApi;
use Library\Json;

class Room
{
    public function go()
    {
        $validate = request()->validator->validate([
            'roomId'   => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $roomId = input()->post('roomId')->value;

        if (!request()->player->online) {
            return Json::encode(["status" => "error", "message" => "Please login first!"]);
        }

        $room = \App\Models\Room::getById($roomId);
        if ($room == null) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/room_not_exists')]);
        }

        if(Config::apiEnabled && request()->player->online) {
            HotelApi::execute('forwarduser', array('user_id' => request()->player->id, 'room_id' => $roomId));
        }
    }
}