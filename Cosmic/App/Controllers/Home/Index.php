<?php
namespace App\Controllers\Home;

use App\Config;

use App\Models\Community;
use App\Models\Player;
use App\Models\Admin;
use App\Models\Core;

use Core\Locale;
use Core\View;

use stdClass;

class Index
{
    public function index()
    {       
      
        $news = Community::getNews(6);
        $rooms = Community::getPopularRooms(5);
        $groups = Community::getPopularGroups(7);

        if(isset(request()->player->id)) {
            $random = Player::getMyOnlineFriends(request()->player->id);
            $currencys = Player::getCurrencys(request()->player->id);
        }
      
        $oftw_userid = Core::Settings()->user_of_the_week ?? null;
        $oftw = Player::getDataByUsername($oftw_userid, ['username', 'look', 'motto']);
        
        View::renderTemplate('Home/home.html', [
            'title'     => !request()->player ? Locale::get('core/title/home') : request()->player->username,
            'page'      => 'home',
            'rooms'     => $rooms,
            'groups'    => $groups,
            'news'      => $news,
            'oftw'      => $oftw,
            'currencys' => isset($currencys) ? $currencys : null,
            'random'    => isset($random) ? $random : null
        ], 10);

        return false;
    }

    public function configuration() {
        Header('Content-Type: text/javascript');
        View::renderTemplate('configuration.html', ['debug' => (Config::debug ? 'true' : 'false'), 'client' => Config::client]);
    }
}
