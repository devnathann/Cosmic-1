<?php
namespace App\Models;

use PDO;
use QueryBuilder;

class Community
{
    /*
     * Get photos queries
     */
  
    public static function getPhotos($limit = 10, $offset = null)
    {
        return QueryBuilder::table('camera_web')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
    }
  
    public static function getPhotoById($id)
    {
        return QueryBuilder::table('camera_web')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->first();
    }

    public static function getPhotosLikes($photoid)
    {
        return QueryBuilder::table('website_photos_likes')->where('photo_id', $photoid)->count();
    }
  
    public static function userAlreadylikePhoto($photoid, $userid)
    {
        return QueryBuilder::table('website_photos_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('photo_id', $photoid)->where('user_id', $userid)->count();
    }
  
    public static function getCurrencyHighscores($type, $limit) 
    {
        return QueryBuilder::table('users_currency')->selectDistinct(array('user_id', 'amount', 'type'))->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('type', $type)->orderBy('amount', 'DESC')->limit($limit)->get();
    }

    /*
     * Get campaigns queries
     */
    public static function getCampaigns($limit = 10)
    {
        return QueryBuilder::table('website_campaigns')->where('enabled', '1')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('timestamp', 'desc')->limit($limit)->get();
    }

    /*
     * Get news queries
     */
    public static function getNews($limit = 10, $offset = null)
    {
        return QueryBuilder::table('website_news')->select('website_news.*')->select('website_news_categories.category')->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                  ->leftJoin('website_news_categories', 'website_news_categories.id', '=', 'website_news.category')->where('website_news.hidden', '0')->offset($offset)->limit($limit)->orderBy('website_news.timestamp', 'desc')->get();
    }

    public static function getArticleById($id)
    {
        return QueryBuilder::table('website_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($id);
    }

    public static function getPostsArticleById($id)
    {
        return QueryBuilder::table('website_news_reactions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('news_id', $id)->get();
    }
  
    public static function getLastArticle()
    {
        return QueryBuilder::table('website_news')->select('id')->select('slug')->orderBy('id', 'desc')->first();
    }
  
    public static function latestArticleReaction($news_id)
    {
        return QueryBuilder::table('website_news_reactions')->where('news_id', $news_id)->where('hidden', '0')->orderBy('timestamp', 'desc')->first();
    }
  
    public static function addNewsReaction($news_id, $player_id, $message)
    {
        $data = array(
            'news_id'     => $news_id,
            'player_id'   => $player_id,
            'message'     => $message,
            'timestamp'   => time()
        );
        return QueryBuilder::table('website_news_reactions')->insert($data);
    }
    /*
     * Feeds queries
     */
  
    public static function getFeeds($limit = 10, $offset = null)
    {
        return QueryBuilder::table('website_feeds')->where('is_hidden', 0)->offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public static function getFeedsByFeedId($feedid)
    {
        return QueryBuilder::table('website_feeds')->where('is_hidden', 0)->where('id', $feedid)->first();
    }
  
    public static function getFeedsByUserid($userid, $limit = 5)
    {
        return QueryBuilder::table('website_feeds')->select('website_feeds.*')->select('users.username')->select('users.look')
                ->join('users', 'website_feeds.from_user_id', '=', 'users.id')
                ->where('to_user_id', $userid)->where('is_hidden', 0)
                ->orderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getFeedsByUserIdOffset($offset, $user_id, $limit = 10)
    {
        return QueryBuilder::table('website_feeds')->where('is_hidden', 0)->offset($offset)->where('to_user_id', $user_id)->orWhere('from_user_id', $user_id)->orderBy('id', 'desc')->limit($limit)->get();
    }


    public static function deleteFeedById($id)
    {
        QueryBuilder::table('website_feeds')->where('id', $id)->delete();
        QueryBuilder::table('website_feeds_reactions')->where('feed_id', $id)->delete();
    }

    public static function getLikes($feedid)
    {
        return QueryBuilder::table('website_feeds_likes')->where('feed_id', $feedid)->count();
    }

    public static function userAlreadylikePost($feedid, $userid)
    {
        return QueryBuilder::table('website_feeds_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('feed_id', $feedid)->where('user_id', $userid)->count();
    }
  
  
    /*
     * Insert queries
     */
  
    public static function insertLike($feedid, $userid)
    {
        $data = array(
            'feed_id'     => $feedid,
            'user_id'     => $userid
        );

        return QueryBuilder::table('website_feeds_likes')->insert($data);
    } 
  
    public static function insertPhotoLike($photoid, $userid)
    {
        $data = array(
            'photo_id'     => $photoid,
            'user_id'     => $userid
        );

        return QueryBuilder::table('website_photos_likes')->insert($data);
    } 

    public static function addFeedToUser($message, $userid, $from)
    {
        $data = array(
            'to_user_id'  => $from,
            'message'     => $message,
            'timestamp'   => time(),
            'from_user_id' => $userid
        );
        return QueryBuilder::table('website_feeds')->insert($data);
    }

    public static function getPopularRooms(int $limit = 10, $offset = null)
    {
        return QueryBuilder::table('rooms')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->offset($offset)->limit($limit)->orderBy('users', 'desc')->get();
    }

    public static function getPopularGroups(int $limit = 10, $offset = null)
    {
        return QueryBuilder::query('SELECT (select id from guilds where id = id) as `id`, (select `name` from guilds where id = id) as `name`, (select `description` from guilds where id = id) as `description`, (select `badge` from guilds where id = id) as `badge` ,count(id) as count FROM guilds_members GROUP BY guild_id ORDER BY count(guild_id) DESC LIMIT '.$limit)->get();
    }
}