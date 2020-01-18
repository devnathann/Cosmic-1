<?php
namespace App\Controllers\Community\Guilds;

use App\Models\Guild;
use App\Models\Player;
use App\Core;

use Core\Locale;
use Core\View;

use Library\Json;

class Topic {
  
    public function __construct() 
    {
        if(!request()->guild->read_forum) {
            if(request()->isAjax()) {
                return Json::encode(["status" => "error", "message" => Locale::get('core/notification/invisible')]);
            }
          
            redirect('/guilds');
        }
    }
  
    public function index($group_id, $topic_id, $page = 1)
    {
        $topic = Guild::getTopicById($this->slug($topic_id));
        $guild = request()->guild;
      
        if($page == 1) {
            $posts = Guild::getPostsById($topic->id, 10);
        } else {
            $offset = ($page - 1) * 10;
            $posts = Guild::getPostsById($topic->id, 10, $offset);
        }
      
        $totalPages   = ceil(count(Guild::getPostsById($topic->id)) / 10);

        foreach($posts as $post) 
        {
            $post->author     = Player::getDataById($post->user_id, array('username', 'look', 'rank', 'account_created'));
            $post->created_at = Core::timediff($post->created_at);
          
            $post->likes      = Guild::getPostLikes($post->id);
          
            $post->content    = Core::FilterString($this->quote($post->message, $post->thread_id));
          
            foreach($post->likes as $likes) {
                $likes->user  = Player::getDataById($likes->user_id, array('username'));
            }
        }

        $topic->slug = $this->slug(Core::filterCharacters($guild->name));
        $topic->total = $totalPages;
        $topic->forum = $guild;
        $topic->posts = $posts;

        View::renderTemplate('Community/Guilds/topic.html', [
            'title'       => $guild->name,
            'page'        => 'forum',
            'topic'       => $topic,
            'currentpage' => $page
        ]);
    }
  
    public function reply()
    {
        $validate = request()->validator->validate([
            'message'   => 'required',
            'topic_id'  => 'required|numeric',
            'guild_id'  => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
      
        $topic_id = input()->post('topic_id')->value;
        $guild_id = input()->post('guild_id')->value;
      
        $topic    = Guild::getTopicById($topic_id);
        $totalPages = ceil(count(Guild::getPostsById($topic->id)) / 10);
        $message  = input()->post('message')->value;
      
        if (request()->player === null) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }
      
        if(!request()->guild->post_messages != false) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/post_not_allowed')]);
        }
      
        if($topic->locked){
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/topic_closed')]);
        }

        $reply_id = Guild::createReply($topic_id, Core::FilterString(Core::tagByUser($message)), request()->player->id); 
        return Json::encode(["status" => "success", "message" =>  Locale::get('core/notification/message_placed'), "replacepage" => "guilds/{$guild_id}/thread/{$topic->id}-" . Core::convertSlug($topic->subject) . "/page/{$totalPages}#{$reply_id}"]);
    }
  
    public function stickyclosethread()
    {
        $validate = request()->validator->validate([
            'id'      => 'required|numeric',
            'action'  => 'required'
        ]);
      
        if(!$validate->isSuccess()) {
            return;
        }
      
        if (request()->player === null) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }
      
        if(!request()->guild->mod_forum != false) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/no_permissions')]);
        }
      
        $post_id = input()->post('id')->value;
        $guild_id = input()->post('guild_id')->value;
      
        $topic = Guild::getTopicById($post_id);
        $topic->slug = Core::convertSlug($topic->subject);

        if(input()->post('action')->value == "sticky") {
            Guild::isSticky(input()->post('id')->value);
            return Json::encode(["status" => "success", "message" => Locale::get('forum/is_sticky'), "replacepage" => "guilds/{$guild_id}/thread/{$topic->id}-{$topic->slug}"]);
        }
      
        Guild::isClosed(input()->post('id')->value);
        echo '{"status":"success","message":"' . Locale::get('forum/is_closed') . '","replacepage":"guilds/'. $guild_id .'/thread/' . $topic->id . '-'. $topic->slug . '"}';
        exit;
    }
  
    public function like()
    {
        $validate = request()->validator->validate([
            'id'   => 'required|numeric'
        ]);
      
        if(!$validate->isSuccess()) {
            return;
        }
      
        if (request()->player === null) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }
      
        $post_id  = input()->post('id')->value;
      
        if (in_array(request()->player->id, array_column(Guild::getPostLikes($post_id), 'user_id'))) {
            return Json::encode(["status" => "error", "message" => Locale::get('core/notification/already_liked')]);
        }

        Guild::insertLike($post_id, request()->player->id);
        $topic = Guild::getTopicByPostId($post_id);
      
        return Json::encode(["status" => "success", "message" => Locale::get('core/notification/liked'), "replacepage" => input()->post('url')->value . "#{$topic->idp}"]);
    }
  
    private function slug($slug)
    {
        return explode('-', $slug)[0];
    }
  
    private function quote($message, $topic_id)
    {
        preg_match_all('/#quote:(\w+)/', $message, $match);

        foreach($match[1] as $match) {
            $post   = Guild::getPostByTopidId($match, $topic_id);
            if(!empty($post)) {
                $quote  = "[quote=" .  Player::getDataById($post->user_id, array('username'))->username . "]" . $post->message . "[/quote]";
                $message = str_replace("#quote:" . $match, $quote, $message);
            }
        }
      
        if (($pos = strpos($message, "#quote:")) !== FALSE) { 
            return $this->quote($message, $topic_id);  
        }
        return $message;
    }
  
}    