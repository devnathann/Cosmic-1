<?php
namespace App\Middleware;

use App\Models\Permission;
use App\Models\Player;

use Core\Session;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AdminAuthMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if (!Session::exists('player_id')) {
            $request->setRewriteUrl(redirect('/'));
        }

        $request->player = Player::getDataById(Session::get('player_id'));

        if (!Permission::exists('housekeeping', $request->player->rank)) {
            $request->setRewriteUrl(redirect('/'));
        }
      
        if ($request->getMethod() == 'get') {
            if ($request->getUrl()->contains('/api')) {
                redirect('/housekeeping');
            }
        }

        if ($request->player === null) {
            return;
        }
    }
}