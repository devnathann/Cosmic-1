<?php
namespace App\Middleware;

use App\Auth;
use App\Config;
use App\Core;
use App\Models\Player;

use Core\Session;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
  
    public function handle(Request $request) : void
    {
        if(Config::installation) {
            redirect('/installation');
        }
        
        if(!Session::exists('player_id')) {
            $remember_me = Auth::loginFromRememberCookie();
            if($remember_me !== NULL) {
               $request->player = $remember_me;
            }
            return;
        }

        $request->player = Player::getDataById(Session::get('player_id'));
        if($request->player == null) {
            return;
        }

        if (Session::get('ip_address') != Core::getIpAddress()) {
            Player::update($request->player->id, ['ip_current' => Core::getIpAddress()]);
        }
    }
}