<?php
namespace App\Controllers\Ajax;

use App\Config;
use Core\Locale;

use Library\HotelApi;

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
            echo '{"status":"error","message":""}';
            exit;
        }

        $room = \App\Models\Room::getById($roomId);
        if ($room == null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/room_not_exists').'"}';
            exit;
        }

        if(Config::apiEnabled && request()->player->online) {
            HotelApi::execute('forwarduser', array('user_id' => request()->player->id, 'room_id' => $roomId));
        }
      
        echo '{"status":"success","message":""}';
    }
}