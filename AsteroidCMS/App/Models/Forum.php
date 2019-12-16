<?php
namespace App\Models;

use PDO;
use QueryBuilder;

class Forum
{
    public static function getCategory()
    {
        return QueryBuilder::table('website_forum_categories')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('position', 'asc')->get();
    }
  
    public static function getForums($catid)
    {
        return QueryBuilder::table('website_forum_index')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('cat_id', $catid)->orderBy('position', 'asc')->get();
    }
  
    public static function getForumTopics($forumid, $limit = 1000, $offset = null)
    {
        return QueryBuilder::table('website_forum_topics')->orderBy('is_sticky', 'DESC')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('forum_id', $forumid)
                    ->offset($offset)->limit($limit)->where('is_visible', '1')->get();
    }
  
    public static function getPostsById($id, $limit = 1000, $offset = null)
    {
        return QueryBuilder::table('website_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $id)->offset($offset)->limit($limit)->get();
    }
  
    public static function getPostById($id)
    {
        return QueryBuilder::table('website_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->first();
    }
  
    public static function getLatestForumPost($topicid)
    {
        return QueryBuilder::table('website_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $topicid)->orderBy('id', 'DESC')->first();
    } 
  
    public static function getPostByTopidId($id, $topicid)
    {
        return QueryBuilder::table('website_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->where('topic_id', $topicid)->first();
    } 
  
    public static function getForumLatestTopic($forumid)
    {
        return QueryBuilder::table('website_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('forum_id', $forumid)->orderBy('created_at', 'DESC')->limit(1)->first();
    }
  
    public static function getPostLikes($postid)
    {
        return QueryBuilder::table('website_forum_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('post_id', $postid)->get();
    }

    public static function getForumById($forumid)
    {
        return QueryBuilder::table('website_forum_index')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $forumid)->first();
    }
  
    public static function getTopicById($id)
    {
        return QueryBuilder::table('website_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->first();
    }
  
    public static function getTopicByPostId($postid)
    {
        return QueryBuilder::table('website_forum_posts')->select('website_forum_topics.*')->select(QueryBuilder::raw('website_forum_posts.id as idp'))
                  ->join('website_forum_topics', 'website_forum_posts.topic_id', '=', 'website_forum_topics.id')
                  ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('website_forum_posts.id', $postid)->first();
    }
  
    public static function latestForumPosts($limit = 5) 
    {
        return QueryBuilder::table('website_forum_posts')->select('users.username')->select('website_forum_topics.title')->select('users.look')
                    ->select('website_forum_posts.user_id')->select('website_forum_posts.created_at')->select('website_forum_posts.id')->select('website_forum_posts.topic_id')
                    ->select('website_forum_posts.created_at')
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('website_forum_posts.created_at', 'DESC')->limit($limit)
                    ->join('website_forum_topics', 'website_forum_posts.topic_id', '=', 'website_forum_topics.id')
                    ->join('users', 'website_forum_posts.user_id',  '=', 'users.id')->get();
    }
  
    public static function createTopic($forumid, $title, $userid, $slug)
    {
        $data = array(
            'title'       => $title,
            'slug'        => $slug,
            'created_at'  => time(),
            'user_id'		  => $userid,
            'forum_id'    => $forumid,
        );
      
        return QueryBuilder::table('website_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function createReply($topicid, $content, $userid)
    {
        $data = array(
            'topic_id'    => $topicid,
            'content'     => $content,
            'created_at'  => time(),
            'user_id'		  => $userid
        );
      
        return QueryBuilder::table('website_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function insertLike($postid, $userid)
    {
        $data = array(
            'post_id' => $postid,
            'user_id' => $userid
        );
      
        return QueryBuilder::table('website_forum_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function updatePostByid($content, $postid)
    {
        $data = array(
            'content'     => $content,
            'updated_at'  => time(),
        );
        return QueryBuilder::table('website_forum_posts')->where('id', $postid)->update($data);
    }  
  
    public static function isSticky($topicid)
    {
        return QueryBuilder::query('UPDATE website_forum_topics SET is_sticky = 1 - is_sticky WHERE id = "'. $topicid .'"');
    }
  
    public static function isClosed($topicid)
    {
        return QueryBuilder::query('UPDATE website_forum_topics SET is_closed = 1 - is_closed WHERE id = "'. $topicid .'"');
    }
}