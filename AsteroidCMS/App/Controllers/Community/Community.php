<?php
namespace App\Controllers\Community;

use App\Core;

use Core\Locale;
use Core\View;

class Community
{
    public function index()
    {
        $news = \App\Models\Community::getNews(6);
        foreach ($news as $item) {
            $item->timestamp = Core::timediff($item->timestamp);
        }

        $rooms = \App\Models\Community::getPopularRooms(10);
        $groups = \App\Models\Community::getPopularGroups(7);

        View::renderTemplate('Community/community.html', [
            'title'  => Locale::get('core/title/community/index'),
            'page'   => 'community',
            'rooms'  => $rooms,
            'groups' => $groups,
            'news'   => $news
        ]);
    }
}