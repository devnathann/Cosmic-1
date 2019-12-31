<?php
namespace App\Controllers\Home;

use App\Config;
use App\Core;

use App\Models\Community;
use App\Models\Player;
use App\Models\Admin;

use Core\Locale;
use Core\View;

use stdClass;

class Index
{
    public function index()
    {       
        $news = Community::getNews(6);
        foreach ($news as $item) {
            $item->timestamp = Core::timediff($item->timestamp);
        }

        $rooms = Community::getPopularRooms(5);
        $groups = Community::getPopularGroups(7);
        
        if(isset(request()->player)) {
            $random = Player::getMyOnlineFriends(request()->player->id);
        }
        
        View::renderTemplate('Home/home.html', [
            'title'     => !request()->player ? Locale::get('core/title/home') : request()->player->username,
            'page'      => 'home',
            'rooms'     => $rooms,
            'groups'    => $groups,
            'news'      => $news,
            'random'    => isset($random) ? $random : null
        ], 10);

        return false;
    }

    public function configuration() {
        Header('Content-Type: text/javascript');
        View::renderTemplate('configuration.html', ['debug' => (Config::debug ? 'true' : 'false'), 'client' => Config::client]);
    }
}