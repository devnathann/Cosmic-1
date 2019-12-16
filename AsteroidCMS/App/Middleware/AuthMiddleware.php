<?php
namespace App\Middleware;

use App\Core;
use App\Models\Player;

use Core\Session;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if(!Session::exists('player_id')) {
            return;
        }

        $request->player = Player::getDataById(Session::get('player_id'));
        if($request->player == null) {
            return;
        }

        if (Session::get('ip_address') != Core::getIpAddress()) {
            Player::update($request->player->id, 'ip_current', Core::getIpAddress());
        }
    }
}