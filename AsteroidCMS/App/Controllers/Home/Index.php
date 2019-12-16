<?php
namespace App\Controllers\Home;

use App\Core;

use Core\Locale;
use Core\View;

class Index
{
    public function index()
    {       
        $news = \App\Models\Community::getNews(6);
        foreach ($news as $item) {
            $item->timestamp = Core::timediff($item->timestamp);
        }

        $rooms = \App\Models\Community::getPopularRooms(10);
        $groups = \App\Models\Community::getPopularGroups(7);
        
        
        View::renderTemplate('Home/home.html', [
            'title'     => !request()->player ? Locale::get('core/title/home') : request()->player->username,
            'page'      => 'home',
            'rooms'     => $rooms,
            'groups'    => $groups,
            'news'      => $news
        ], 10);

        return false;
    }

    public function configuration() {
        Header('Content-Type: text/javascript');
        View::renderTemplate('configuration.html');
    }
}