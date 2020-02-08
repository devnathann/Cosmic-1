<?php
namespace App\Controllers\Community;

use App\Config;
use App\Core;

use App\Models\Community;
use App\Models\Permission;
use App\Models\Player;
use App\Models\Admin;

use Core\View;
use Core\Locale;

use Library\Json;

class Articles
{
    public function more()
    {
        echo Json::encode(['articles' => Community::getNews(6, input()->post('offset')->value)]);
    }

    public function hide() {

        $validate = request()->validator->validate([
            'post' => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $news_id = input()->post('post')->value;

        if(Permission::exists('housekeeping_moderation_tools', request()->player->id)) {
            response()->json(["status" => "error", "is_hidden" => "show", "message" => Locale::get('core/notification/something_wrong')]);
        }

        if(Community::isNewsHidden($news_id)->hidden == 0) {
            Community::hideNewsReaction($news_id, '1');
            response()->json(["status" => "success", "is_hidden" => "hide", "message" => Locale::get('website/article/reaction_hidden_yes')]);
        }

        Community::hideNewsReaction($news_id, '0');
        response()->json(["status" => "success", "is_hidden" => "show", "message" => Locale::get('website/article/reaction_hidden_no')]);
    }

    public function add()
    {
        $validate = request()->validator->validate([
            'articleid'   =>   'required|numeric',
            'message'  =>   'required'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $article = Community::getArticleById(input()->post('articleid')->value);

        if (empty($article)) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }

        $message = Core::filterString(Core::tagByUser(input()->post('message')->value, $article->id));

        $wordfilter = Admin::getWordFilters();

        foreach ($wordfilter as $word) {
          if (stripos($message, $word->key) !== false) {
            response()->json(["status" => "error", "message" => Locale::get('website/article/forbidden_words')]);
          }
        }

        Community::addNewsReaction($article->id, request()->player->id, $message);

        response()->json(["status" => "success", "message" => Locale::get('core/notification/message_placed'), "bericht" => $message, "figure" => request()->player->look]);
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
