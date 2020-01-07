<?php
namespace App\Controllers\Community\Guilds;

use App\Models\Guild;
use App\Models\Player;

use App\Core;

use Core\Locale;
use Core\View;

class Home
{
    public function index()
    { 
        $forums = Guild::getCategory(request()->player->id);
        
        foreach($forums as $forum) {
            $forum->slug = Core::convertSlug($forum->name);
        }
      
        $public = Guild::getPublicGuilds();

        foreach($public as $guild) {
            $guild->user = Guild::getGuilds($guild->id, request()->player->id);
            $guild->slug = Core::convertSlug($guild->name);
        }
      
        $latestPosts = Guild::latestForumPosts();
        foreach($latestPosts as $latest) {
            $latest->slug   = Core::convertSlug($latest->subject);
            $latest->author = Player::getDataById($latest->user_id, array('username', 'look'));
        }
     
        View::renderTemplate('Community/Guilds/index.html', [
            'title'   => Locale::get('core/title/community/forum'),
            'page'    => 'forum',
            'forums'  => $forums,
            'public'  => $public,
            'latestposts' => $latestPosts
        ]);
    }
}