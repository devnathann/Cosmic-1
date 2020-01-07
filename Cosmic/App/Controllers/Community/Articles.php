<?php
namespace App\Controllers\Community;

use App\Models\Community;
use App\Models\Core;
use App\Models\Player;

use Core\View;
use Core\Locale;

class Articles
{
    public function more()
    {
        echo json_encode(array('articles' => Community::getNews(6, input()->post('offset')->value)));
    }

    public function hide() {
      
        $validate = request()->validator->validate([
            'post' => 'required|numeric'
        ]);
      
        if(!$validate->isSuccess()) {
            exit;
        }
      
        $news_id = input()->post('post')->value;
      
        if(Core::permission('housekeeping_moderation_tools', request()->player->id)) {
            echo '{"status":"success","is_hidden":"show","message":"' . Locale::get('core/notification/something_wrong') . '"}';
            exit;
        }
        
        $is_hidden = Core::getField('website_news_reactions', 'hidden', 'id', $news_id);
        if($is_hidden == 0) {
            Community::hideNewsReaction($news_id, '1');
            echo '{"status":"success","is_hidden":"hide","message":"' . Locale::get('website/article/reaction_hidden_yes') . '"}';
            exit;
        }

        Community::hideNewsReaction($news_id, '0');
        echo '{"status":"success","is_hidden":"show","message":"' . Locale::get('website/article/reaction_hidden_no') . '"}';
    }
  
    public function add()
    {
        $validate = request()->validator->validate([
            'articleid'   =>   'required|numeric',
            'message'  =>   'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $article = Community::getArticleById(input()->post('articleid')->value);

        if (empty($article)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '"}';
            exit;
        }
      
        $message = \App\Core::tagByUser(input()->post('message')->value, $article->id);
        Community::addNewsReaction($article->id, request()->player->id, $message);
        
        echo '{"status":"success","message":"' . Locale::get('core/notification/message_placed') . '","bericht":"' . $message . '","figure":"' . request()->player->look . '"}';
    }
  
    public function index($slug = null)
    {
        $route = explode('-', $slug ?? Community::getLastArticle()->id . '-' . $slug);

        $article = Community::getArticleById($route[0]);
        if (empty($article)) {
            redirect('/');
        }

        $player = Player::getDataById($article->author, array('username', 'look'));

        if ($player != null) {
            $article->author = $player;
        }
      
        $posts = Community::getPostsArticleById($article->id);
        foreach($posts as $post) {
            $post->author = Player::getDataById($post->player_id, array('username', 'look'));
        }

        $latest_news = Community::getNews();
    

        View::renderTemplate('Community/article.html', [
            'title'         => $article->title,
            'page'          => 'article',
            'latest_news'   => $latest_news,
            'article'       => $article,
            'posts'         => $posts
        ]);
    }
}