<?php
namespace App\Controllers\Community;

use App\Models\Core as ModelCore;
use App\Models\Forum as Forums;
use App\Models\Player;
use App\Core;

use Core\Locale;
use Core\View;

class Forum
{
    public function index()
    {
        $forums = Forums::getCategory();
      
        foreach($forums as $cat) {
            $cat->forum = Forums::getForums($cat->id);
          
            foreach($cat->forum as $forum){
                $forum->count_topics = count(Forums::getForumTopics($forum->id));
              
                if($forum->count_topics > 0){
                    $forum->latest_topic              = Forums::getForumLatestTopic($forum->id);
                    $forum->latest_topic->author      = Player::getDataById($forum->latest_topic->user_id, 'username'); 
                    $forum->latest_topic->created_at  = Core::timediff($forum->latest_topic->created_at);
                }
            }
        }
      
        $latestPosts = Forums::latestForumPosts();
        foreach($latestPosts as $latest) {
            $latest->author     = Player::getDataById($latest->user_id, array('username', 'look'));
        }
      
        View::renderTemplate('Community/Forum/index.html', [
            'title'   => Locale::get('core/title/community/forum'),
            'page'    => 'forum',
            'forums'  => $forums,
            'latestposts' => $latestPosts
        ]);
    }
  
    public function category($slug, $page = 1)
    {
        $forums = Forums::getForumById($this->slug($slug));
      
        if(!$forums) {
            redirect('/forum');
        }
      
        if($page == 1) {
            $topics = Forums::getForumTopics($this->slug($slug), 10);
        } else {
            $offset = ($page - 1) * 10;
            $topics = Forums::getForumTopics($this->slug($slug), 10, $offset);
        }
      
        $totalPages   = ceil(count(Forums::getForumTopics($this->slug($slug))) / 10);
      
        foreach($topics as $topic)
        {
            $topic->author      = Player::getDataById($topic->user_id, array('username', 'look'));
            $topic->latest_post = Forums::getLatestForumPost($topic->id);
            $topic->totalposts  = count(Forums::getPostsById($topic->id));
          
            if($topic->latest_post)
            {
                $topic->latest_post->author     = Player::getDataById($topic->latest_post->user_id, array('username', 'look'));
            }
        }
      
        $forums->topics = $topics;
        $forums->total = $totalPages;

        View::renderTemplate('Community/Forum/category.html', [
            'title'   => $forums->title,
            'page'    => 'forum',
            'forums'  => $forums,
            'currentpage' => $page
        ]);
    }
  
    public function topic($slug, $page = 1)
    {
        $topic = Forums::getTopicById($this->slug($slug));
      
        if(!$topic) {
            redirect('/forum');
        }
      
        $forum = Forums::getForumById($topic->forum_id);
      
        if($page == 1) {
            $posts = Forums::getPostsById($topic->id, 10);
        } else {
            $offset = ($page - 1) * 10;
            $posts = Forums::getPostsById($topic->id, 10, $offset);
        }
      
        $totalPages   = ceil(count(Forums::getPostsById($topic->id)) / 10);

        foreach($posts as $post) 
        {
            $post->author     = Player::getDataById($post->user_id, array('username', 'look', 'rank', 'account_created'));
            $post->created_at = Core::timediff($post->created_at);
          
            $post->likes      = Forums::getPostLikes($post->id);
          
            $post->content = $this->quote($post->content, $post->topic_id);
          
            foreach($post->likes as $likes) {
                $likes->user  = Player::getDataById($likes->user_id, array('username'));
            }
        }
      
        $topic->total = $totalPages;
        $topic->forum = $forum;
        $topic->posts = $posts;
      
        View::renderTemplate('Community/Forum/topic.html', [
            'title'   => $topic->title,
            'page'    => 'forum',
            'topic'   => $topic,
            'currentpage' => $page
        ]);
    }
  
    public function create()
    {
        $validate = request()->validator->validate([
            'title'     => 'required|min:6|max:50',
            'message'   => 'required',
            'cat_id'    => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $title    = input()->post('title')->value;
        $message  = input()->post('message')->value;
        $cat_id   = input()->post('cat_id')->value;
        $slug     = Core::convertSlug($title);
        $forums   = Forums::getForumById($cat_id);
      
        if (request()->player === null || $forums != null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        if ($forums->max_rank >= request()->player->rank) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }   
      
        $topic_id = Forums::createTopic($cat_id, Core::FilterString($title), request()->player->id, $slug); 
        $reply_id = Forums::createReply($topic_id, Core::FilterString(Core::tagByUser($message)), request()->player->id);
      
        echo '{"status":"success","message":"' . Locale::get('core/notification/message_placed') . '","replacepage":"forum/thread/' . $topic_id . '-'. $slug . '"}';
    }
  
    public function reply()
    {
        $validate = request()->validator->validate([
            'message'   => 'required',
            'topic_id'  => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $topic_id = input()->post('topic_id')->value;
        $topic    = Forums::getTopicById($topic_id);
        $totalPages = ceil(count(Forums::getPostsById($topic->id)) / 10);
        $message  = input()->post('message')->value;
      
        if (request()->player === null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        if($topic->is_closed){
            echo '{"status":"error","message":"'.Locale::get('core/notification/topic_closed').'"}';
            exit;
        }

        $reply_id = Forums::createReply($topic_id, Core::FilterString(Core::tagByUser($message)), request()->player->id); 
        echo '{"status":"success","message":"' . Locale::get('core/notification/message_placed') . '","replacepage":"forum/thread/' . $topic->id . '-'. $topic->slug . '/page/'. $totalPages . '#' . $reply_id . '"}';
    }
  
    public function edit()
    {
        $validate = request()->validator->validate([
            'id'   => 'required|numeric'
        ]);
      
        if(!$validate->isSuccess()) {
            exit;
        }
        
        $post = Forums::getPostById(input()->post('id')->value);
      
        if(empty($post) && $post->user_id == request()->player->id) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        $topic = Forums::getTopicById($post->topic_id);
        $page = ceil(count(Forums::getPostsById($topic->id)) / 10);
      
        if($topic->is_closed) {
            echo '{"status":"error","message":"'.Locale::get('forum/is_closed').'"}';
            exit;
        }
      
        if(input()->post('action')->value == "view") {
            echo '{"status":"success","data":"'. base64_encode($post->content) .'"}';
            exit;
        }
      
        Forums::updatePostByid(Core::FilterString(input()->post('message')->value), input()->post('id')->value);
        echo '{"status":"success","replacepage":"forum/thread/' . $topic->id . '-'. $topic->slug . '/page/'. $page . '#' . $post->id . '"}';
    }
  
    public function like()
    {
        $validate = request()->validator->validate([
            'id'   => 'required|numeric'
        ]);
      
        if(!$validate->isSuccess()) {
            exit;
        }
      
        if (request()->player === null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        $post_id  = input()->post('id')->value;
      
        if (in_array(request()->player->id, array_column(Forums::getPostLikes($post_id), 'user_id'))) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/already_liked') . '"}';
            exit;
        }

        Forums::insertLike($post_id, request()->player->id);
        $topic = Forums::getTopicByPostId($post_id);
      
        echo '{"status":"success","message":"' . Locale::get('core/notification/liked') . '","replacepage":"' . input()->post('url')->value . '#' . $topic->idp . '"}';
    }
  
    public function stickyclosethread()
    {
        $validate = request()->validator->validate([
            'id'      => 'required|numeric',
            'action'  => 'required'
        ]);
      
        if(!$validate->isSuccess()) {
            exit;
        }
      
        if (request()->player === null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        if(ModelCore::permission('housekeeping_moderation_tools', request()->player->id)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/no_permissions') . '"}';
            exit;
        }
      
        $post_id = input()->post('id')->value;
        $topic = Forums::getTopicById($post_id);

        if(input()->post('action')->value == "sticky") {
            Forums::isSticky(input()->post('id')->value);
            echo '{"status":"success","message":"' . Locale::get('forum/is_sticky') . '","replacepage":"forum/thread/' . $topic->id . '-'. $topic->slug . '"}';
            exit;
        }
      
        Forums::isClosed(input()->post('id')->value);
        echo '{"status":"success","message":"' . Locale::get('forum/is_closed') . '","replacepage":"forum/thread/' . $topic->id . '-'. $topic->slug . '"}';
        exit;
    }
  
    public static function report()
    {
            echo '{"status":"error","message":"' . Locale::get('core/notification/no_permissions') . '"}';
            exit;
    }
  
    private function slug($slug)
    {
        return explode('-', $slug)[0];
    }
  
    private function quote($message, $topic_id)
    {
        preg_match_all('/#quote:(\w+)/', $message, $match);

        foreach($match[1] as $match) {
            $post   = Forums::getPostByTopidId($match, $topic_id);
            if(!empty($post)) {
                $quote  = "[quote=" .  Player::getDataById($post->user_id, array('username'))->username . "]" . $post->content . "[/quote]";
                $message = str_replace("#quote:" . $match, $quote, $message);
            }
        }
      
        if (($pos = strpos($message, "#quote:")) !== FALSE) { 
            return $this->quote($message, $topic_id);  
        }
        return $message;
    }
}