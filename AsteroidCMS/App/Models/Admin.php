<?php
namespace App\Models;

use App\Config;
use PDO;
use QueryBuilder;

class Admin
{
    /**
     * Home queries
     */

    public static function getLatestPlayers($limit = 100)
    {
        return QueryBuilder::table('users')->select('id')->select('mail')->select('username')->select('ip_current')
                  ->select('ip_register')->select('online')->select('last_login')->select('look')->OrderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getMailLogs($player_id, $limit = 100)
    {
        return QueryBuilder::table('website_user_logs_email')->where('user_id', $player_id)->orderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getStaffLogsByPlayerId($player_id, $limit = 500)
    {
        return QueryBuilder::table('website_staff_logs')->where('player_id', $player_id)->OrderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getNameChanges($limit = 100)
    {
        return QueryBuilder::table('namechange_log')->OrderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getTradeLogs($player_id, $limit = 1000)
    {
        return QueryBuilder::table('room_trade_log')->select('room_trade_log.*')->where('user_one_id', $player_id)->orWhere('user_two_id', $player_id)->OrderBy('room_trade_log.id', 'desc')->limit($limit)->get();
    }
  
    public static function getTradeLogItems($item_id)
    {
        return QueryBuilder::table('room_trade_log_items')->select('room_trade_log_items.*')->select('items_base.item_name')->where('room_trade_log_items.id', $item_id)
                  ->join('items', 'room_trade_log_items.item_id', '=', 'items.id')
                  ->join('items_base', 'items.item_id', '=', 'items_base.id')->get();
    }

    public static function getNameChangesById($user_id)
    {
        return QueryBuilder::table('namechange_log')->where('user_id', $user_id)->get();
    }

    public static function getOnlinePlayers($limit = 1000)
    {
        return QueryBuilder::table('users')->select('username')->select('ip_register')->select('ip_current')->select('look')->select('id')
                                    ->select('mail')->where('online', '1')->OrderBy('id', 'desc')->limit($limit)->get();
    }

    /**
     * Alert queries
     */

    public static function getAlertMessages()
    {
        return QueryBuilder::table('website_alert_messages')->get();
    }

    public static function getAlertMessagesById($id)
    {
        return QueryBuilder::table('website_alert_messages')->where('id', $id)->first();
    }

    /**
     * Ban queries
     */

    public static function getAllBans($limit = 1000)
    {
        return QueryBuilder::table('bans')->OrderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getBanMessages()
    {
        return QueryBuilder::table('website_ban_messages')->get();
    }

    public static function getBanMessagesById($id)
    {
        return QueryBuilder::table('website_ban_messages')->where('id', $id)->first();
    }

    public static function getBanTime($user_rank)
    {
        return QueryBuilder::table('website_ban_types')->where('min_rank', '<=', $user_rank)->orWhereNull('min_rank')->get();
    }

    public static function getBanTimeById($id)
    {
        return QueryBuilder::table('website_ban_types')->where('id', $id)->first();
    }

    public static function createBan($type, $ban_data, $reason, $added_by, $expire)
    {
        $data = array(
            'type' => $type,
            'data' => $ban_data,
            'reason' => $reason,
            'added_by' => $added_by,
            'added_date' => time(),
            'expire' => $expire + time()
        );

        return QueryBuilder::table('bans')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function deleteBan($data)
    {
        return QueryBuilder::table('bans')->where('data', $data)->delete();
    }

    /**
     * Logs queries
     */

    public static function getAllLogs($limit = 500)
    {
        return QueryBuilder::query("select user_to_id, user_from_id, message, `timestamp`, 'MESSENGER' as type from chatlogs_private union all  select user_to_id, user_from_id, message, `timestamp`, 'WHISPER' as type from chatlogs_room WHERE user_to_id > 0 AND user_from_id > 0 union all select user_to_id, user_from_id, message, `timestamp`, 'MESSAGE' as type from chatlogs_room WHERE user_to_id = 0 AND user_from_id > 0 Order by `timestamp` DESC LIMIT " . $limit . "")->get();
    }

    public static function getChatLogs($player_id, $limit = 500)
    {
        return QueryBuilder::query("select user_to_id, user_from_id, message, `timestamp`, 'MESSENGER' as type from chatlogs_private where user_from_id = '" . $player_id . "' union all  select user_to_id, user_from_id, message, `timestamp`, 'WHISPER' as type from chatlogs_room WHERE user_from_id = '" . $player_id . "' and user_to_id > 0 union all select user_to_id, user_from_id, message, `timestamp`, 'MESSAGE' as type from chatlogs_room WHERE user_from_id = '" . $player_id . "' and user_to_id = 0 Order by `timestamp` DESC LIMIT " . $limit . "")->get();
    }

    public static function getCompareLogs($players, $limit = 2000)
    {
        return QueryBuilder::query("SELECT * FROM chatlogs_room WHERE user_from_id IN ($players) ORDER BY timestamp DESC LIMIT $limit")->get();
    }

    public static function getMessengerLogs($user_id, $limit = 500)
    {
        return QueryBuilder::table('chatlogs_private')->where('user_from_id', $user_id)->OrderBy('timestamp', 'desc')->limit($limit)->get();
    }

    public static function getStaffLogs($limit = 100)
    {
        return QueryBuilder::table('website_staff_logs')->OrderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getClones($last_ip, $reg_ip, $limit = 1000)
    {
        return QueryBuilder::table('users')->select('id')->select('username')->select('ip_current')->select('ip_register')->select('online')
                ->select('last_login')->where('ip_current', $last_ip)->orWhere('ip_register', $reg_ip)->limit($limit)->get();
    }
    /*
     * Wordfilter queries
     */
    public static function getWordFilters()
    {
        return QueryBuilder::table('wordfilter')->get();
    }

    public static function getWordsByString($string, $limit = 10)
    {
        return QueryBuilder::table('wordfilter')->select('key')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('key', 'LIKE', $string . '%')->limit($limit)->get();
    }

    public static function getWordFilterByWord($word)
    {
        return QueryBuilder::table('wordfilter')->where('key', $word)->first();
    }

    public static function addWordFilter($word, $added_by)
    {
        $data = array(
            'key' => $word,
            'replacement' => '{blacklisted}',
        );

        return QueryBuilder::table('wordfilter')->insert($data);
    }

    public static function deleteWordByWord($word)
    {
        return QueryBuilder::table('wordfilter')->where('key', $word)->delete();
    }


    /*
     * News queries
     */

    public static function getNews()
    {
        return QueryBuilder::table('website_news')
            ->select('website_news.id')
            ->select('website_news.title')
            ->select('website_news.short_story')
            ->select('website_news.full_story')
            ->select('website_news.images')
            ->select('website_news.timestamp')
            ->select('website_news.author')
            ->select(QueryBuilder::raw('website_news_categories.category as cat_name'))
            ->join('website_news_categories', 'website_news.category', '=', 'website_news_categories.id')->orderBy('id', 'desc')->get();
    }

    public static function getNewsCategories()
    {
        return QueryBuilder::table('website_news_categories')->get();
    }

    public static function getNewsById($id)
    {
        return QueryBuilder::table('website_news')->select('id')->select('title')->select('category')->select('short_story')->select('full_story')->select('images')->select('header')->select('timestamp')->select('author')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->orderBy('id', 'desc')->first();
    }

    public static function getNewsCategoryById($id)
    {
        return QueryBuilder::table('website_news_categories')->where('id', $id)->first();
    }

    public static function addNews(String $title, String $short_story, String $full_story, $category, $header, $images, int $authorId)
    {
        $data = array(
            'slug' => \App\Core::convertSlug($title),
            'title' => $title,
            'short_story' => $short_story,
            'full_story' => $full_story,
            'category' => $category,
            'header' => $header,
            'images' => $images,
            'author' => $authorId,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function editNews(int $id, String $title, String $short_story, String $full_story, $category, $header, $images, int $authorId)
    {
        $data = array(
            'slug' => \App\Core::convertSlug($title),
            'title' => $title,
            'short_story' => $short_story,
            'full_story' => $full_story,
            'category' => $category,
            'header' => $header,
            'images' => $images,
            'author' => $authorId
        );

        return QueryBuilder::table('website_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->update($data);
    }

    public static function removeNews(int $id)
    {
        return QueryBuilder::table('website_news')->where('id', $id)->delete();
    }

    public static function addNewsCategory($category)
    {
        $data = array(
            'category' => $category
        );

        return QueryBuilder::table('website_news_categories')->insert($data);
    }

    public static function editNewsCategory($id, $category)
    {
        $data = array(
            'category' => $category
        );

        return QueryBuilder::table('website_news_categories')->where('id', $id)->update($data);
    }

    public static function removeNewsCategory($id)
    {
        return QueryBuilder::table('website_news_categories')->where('id', $id)->delete();
    }

    public static function updateReports($status, $itemid)
    {
        $data = array(
            'closed' => $status
        );

        return QueryBuilder::table('website_reports')->where('id', $itemid)->update($data);
    }

    /*
     * Player queries
     */
    public static function getPlayersByString($string, $limit = 10)
    {
        return QueryBuilder::table('users')->select('username')->select('id')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('username', 'LIKE', $string . '%')->limit($limit)->get();
    }

    public static function changePlayerSettings($email, $motto, $credits, $pin_code, $user_id)
    {
        $data = array(
            'mail'         => $email,
            'motto'         => $motto,
            'credits'       => $credits,
            'pincode'       => $pin_code
        );

        return QueryBuilder::table('users')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $user_id)->update($data);
    }

    public static function getPopularRooms($limit = 100)
    {
        return QueryBuilder::table('rooms')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('users', 'desc')->limit($limit)->get();
    }

    public static function getRoomsByString($string, $limit = 10)
    {
        return QueryBuilder::table('rooms')->select('name')->select('id')->select('owner_name')->select('users')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('name', 'LIKE', $string . '%')->orderBy('users', 'desc')->limit($limit)->get();
    }

    public static function getPlayersByIp($ip)
    {
        return QueryBuilder::table('users')->select('id')->select('username')->select('ip_current')->select('ip_register')
            ->select('last_login')->select('online')->where('ip_current', $ip)->orWhere('ip_register', $ip)->get();
    }
    
    /*
     * Helptool queries
     */

    public static function getHelpTickets()
    {
        return QueryBuilder::table('website_helptool_requests')
                    ->select('website_helptool_requests.id')
                    ->select('website_helptool_requests.subject')->select('website_helptool_requests.player_id')->select('users.username')
                    ->select('website_helptool_requests.timestamp')->select('website_helptool_requests.status')
                    ->join('users', 'users.id', '=', 'website_helptool_requests.player_id')
                    ->whereNotNull('website_helptool_requests.player_id')->orderBy('website_helptool_requests.id', 'desc')->limit(350)->get();
    }

    public static function getHelpTicketById($id)
    {
        return QueryBuilder::table('website_helptool_requests')->find($id);
    }

    public static function getHelpTicketReactions(int $id)
    {
        return QueryBuilder::table('website_helptool_reactions')->where('request_id', $id)->get();
    }

    public static function getHelpTicketLogs(int $id)
    {
        return QueryBuilder::table('website_helptool_logs')->where('target', $id)->orderBy('timestamp', 'desc')->get();
    }

    public static function updateTicketStatus($action, $id)
    {
        $data = array(
            'status' => $action
        );

        return QueryBuilder::table('website_helptool_requests')->where('id', $id)->update($data);
    }

    public static function sendTicketMessage(String $message, int $id, int $player_id)
    {
        $data = array(
            'request_id' => $id,
            'practitioner_id' => $player_id,
            'message' => $message,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_helptool_reactions')->insert($data);
    }

    public static function getLatestChangeStatus(int $id)
    {
        return QueryBuilder::table('website_helptool_logs')->where('target', $id)->orderBy('id', 'desc')->first();
    }

    /*
     * FAQ queries
     */

    public static function getFAQ()
    {
        return QueryBuilder::table('website_helptool_faq')
                ->select('website_helptool_faq.id')
                ->select('website_helptool_faq.title')
                ->select('website_helptool_faq.timestamp')
                ->select('website_helptool_faq.author')
                ->select(QueryBuilder::raw('website_helptool_categories.category as cat_name'))
                ->join('website_helptool_categories', 'website_helptool_faq.category', '=', 'website_helptool_categories.id')->orderBy('id', 'desc')->get();
    }

    public static function getFAQCategory()
    {
        return QueryBuilder::table('website_helptool_categories')->get();
    }

    public static function getFAQById($id)
    {
        return QueryBuilder::table('website_helptool_faq')->where('id', $id)->first();
    }

    public static function getFAQCategoryById($id)
    {
        return QueryBuilder::table('website_helptool_categories')->where('id', $id)->first();
    }

    public static function addFAQ($title, $story, $category, $author)
    {
        $data = array(
            'title' => $title,
            'slug' => \App\Core::convertSlug($title),
            'desc' => $story,
            'category' => $category,
            'timestamp' => time(),
            'author' => $author
        );

        return QueryBuilder::table('website_helptool_faq')->insert($data);
    }

    public static function editFAQ($id, $title, $story, $category, $author)
    {
        $data = array(
            'title' => $title,
            'slug' => \App\Core::convertSlug($title),
            'desc' => $story,
            'category' => $category,
            'timestamp' => time(),
            'author' => $author
        );

        return QueryBuilder::table('website_helptool_faq')->where('id', $id)->update($data);
    }

    public static function removeFAQ($id)
    {
        return QueryBuilder::table('website_helptool_faq')->where('id', $id)->delete();
    }

    public static function addFAQCategory($category)
    {
        $data = array(
            'category' => $category
        );

        return QueryBuilder::table('website_helptool_categories')->insert($data);
    }

    public static function editFAQCategory($id, $category)
    {
        $data = array(
            'category' => $category
        );

        return QueryBuilder::table('website_helptool_categories')->where('id', $id)->update($data);
    }

    public static function removeFAQCategory($id)
    {
        return QueryBuilder::table('website_helptool_categories')->where('id', $id)->delete();
    }

    /*
     * Shop Logs
     */

    public static function getOffers()
    {
        return QueryBuilder::table('website_shop_offers')->get();
    }
    
    /*
     * Catalog queries
     */
    public static function getCatalogPages()
    {
        return QueryBuilder::table('catalog_pages')->orderby('id', 'desc')->get();
    }

    public static function getCatalogPagesById($id)
    {
        return QueryBuilder::table('catalog_pages')->find($id);
    }

    public static function getCatalogItemsByPageId($page_id)
    {
        return QueryBuilder::table('catalog_items')->where('page_id', $page_id)->orderBy('id', 'desc')->get();
    }

    public static function getCatalogItemsByItemIds($item_ids)
    {
        return QueryBuilder::table('catalog_items')->where('item_ids', $item_ids)->first();
    }

    public static function getFurnitureById($id)
    {
        return QueryBuilder::table('items_base')->find($id);
    }
  
    public static function updateCatalogPages($catid, $caption, $page_teaser, $page_headline, $parent_id, $page_layout, $visible, $enabled) 
    {
        $data = array(
            'caption' => $caption,
            'page_teaser' => $page_teaser,
            'page_headline' => $page_headline,
            'parent_id' => $parent_id,
            'page_layout' => $page_layout,
            'visible' => $visible,
            'enabled' => $enabled
        );
      
        $catalogue = self::getCatalogPagesById($catid);
        if($catalogue) {
            return QueryBuilder::table('catalog_pages')->where('id', $catid)->update($data);
        }
      
        return QueryBuilder::table('catalog_pages')->insert($data);
    }
  
    public static function updateFurniture($object, $furni_id)
    {
        $lastItemBase = QueryBuilder::table('items_base')->orderBy('id', 'desc')->limit(1)->first();
        $lastItemCatalog = QueryBuilder::table('catalog_items')->orderBy('id', 'desc')->limit(1)->first();
        
        $furnidata = self::getFurnitureById($furni_id);
        if(!empty($furnidata)) {
            QueryBuilder::table('items_base')->where('id', $furni_id)->update($object['items_base']);
            return QueryBuilder::table('catalog_items')->where('id', $furni_id)->update($object['catalog_items']);
        } else {
          
            $object['catalog_items']['item_ids'] = $lastItemBase->id + 1;
            $object['catalog_items']['id'] = $lastItemCatalog->id + 1;
            $object['items_base']['id'] = $lastItemBase->id + 1;
          
            QueryBuilder::table('items_base')->insert($object['items_base']);
            return QueryBuilder::table('catalog_items')->insert($object['catalog_items']); 
        }
    }

    /*
     * Rank Management & Permissions queries
     */

    public static function getRanks($allRanks = false)
    {
        if($allRanks) {
            return QueryBuilder::table('permissions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('id', 'asc')->get();
        }

        return QueryBuilder::table('permissions')->where('id', '!=', 1)->where('id', '!=', 2)->orderBy('id', 'asc')->get();
    }

    public static function getAllWebPermissions($limit = 500)
    {
        return QueryBuilder::table('website_permissions')->limit($limit)->get();
    }
  
    public static function getWebPermissions($id, $limit = 500)
    {
        return QueryBuilder::query("SELECT description FROM website_permissions WHERE id IN ($id)")->limit($limit)->get();
    }

    public static function getRankById($id)
    {
        return QueryBuilder::table('permissions')->select('id')->select('name')->where('id', $id)->first();
    }

    public static function getRoles($string = null, $allRanks = false)
    {
        if($allRanks) {
            return QueryBuilder::table('permissions')->select('rank_name')->select('id')->orderBy('id', 'desc')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('rank_name', 'LIKE ', '%' . $string . '%')->get();
        }

        return QueryBuilder::table('permissions')->select('rank_name')->select('id')->where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 8)->orderBy('id', 'desc')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('name', 'LIKE ', '%' . $string . '%')->get();
    }

    public static function addRank($commands, $permissions)
    {
        $rankId = QueryBuilder::table('permissions')->insert((array)$commands);
        foreach($permissions as $row) {
            QueryBuilder::table('website_permissions_ranks')->insert(array('rank_id' => $rankId, 'permission_id' => $row));
        }
        return true;
    }
  
    public static function changeMinimumRank($command, $rank)
    {
        return QueryBuilder::table('permission_commands')->where('command_id', $command)->update(array('minimum_rank' => $rank));
    }

    public static function createPermission($role, $permission)
    {
        $data = array(
            'rank_id' => $role,
            'permission_id'  => $permission,
        );

        return QueryBuilder::table('website_permissions_ranks')->insert($data);
    }

    public static function deletePermission($permission)
    {
        return QueryBuilder::table('website_permissions_ranks')->where('id', $permission)->delete();
    }

    public static function roleExists($role, $permission)
    {
        return QueryBuilder::table('website_permissions_ranks')->where('permission_id', $permission)->where('rank_id', $role)->count();
    }

    public static function getBanLogByUserId($user_id){
        return QueryBuilder::table('bans')->select('bans.*')->select('users.username')
                    ->join('users', 'bans.user_id', '=', 'users.id')->where('bans.user_id', $user_id)->get();
    }
  
    public static function getForums()
    {
        return QueryBuilder::table('website_forum_index')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('position', 'asc')->get();
    }
  
    public static function getCategoryById($id)
    {
        return QueryBuilder::table('website_forum_categories')->where('id', $id)->orderBy('position', 'asc')->first();
    }
  
    public static function createCategory($title, $description, $min_rank, $position)
    {
        $data = array(
            'name'        => $title,
            'description' => $description,
            'min_rank'    => $min_rank,
            'position'    => $position
        );
      
        return QueryBuilder::table('website_forum_categories')->insert($data);
    } 
  
   public static function editCategory($id, $title, $description, $min_rank, $position)
   {
        $data = array(
            'name'        => $title,
            'description' => $description,
            'min_rank'    => $min_rank,
            'position'    => $position
        );
      
        return QueryBuilder::table('website_forum_categories')-where('id', $id)->update($data);
    } 
  
   public static function createForum($title, $description, $category, $imagePath, $min_rank, $position, $slug)
    {
        $data = array(
            'title'         => $title,
            'description'   => $description,
            'cat_id'        => $category,
            'image'         => $imagePath,
            'min_rank'      => $min_rank,
            'position'      => $position, 
            'slug'          => $slug
        );
      
        return QueryBuilder::table('website_forum_index')->insert($data);
    } 
  
   public static function editForum($id, $title, $description, $category, $imagePath, $min_rank, $position, $slug)
   {
        $data = array(
            'title'         => $title,
            'description'   => $description,
            'cat_id'        => $category,
            'image'         => $imagePath,
            'min_rank'      => $min_rank,
            'position'      => $position, 
            'slug'          => $slug
        );
      
        return QueryBuilder::table('website_forum_index')-where('id', $id)->update($data);
    }
  
    public static function deleteForum($id)
    {
        return QueryBuilder::table('website_forum_index')->where('id', $id)->delete();
    }

    public static function deleteCategory($id)
    {
        return QueryBuilder::table('website_forum_categories')->where('id', $id)->delete();
    }
  
   public static function offerCreate($currencys, $amount, $price, $lang, $offer_id, $private_key)
    {
        $data = array(
            'currency'        => $currencys,
            'amount'          => $amount,
            'price'           => $price,
            'offer_id'        => $offer_id,
            'private_key'     => $private_key,
            'lang'            => $lang
        );
      
        return QueryBuilder::table('website_shop_offers')->insert($data);
    } 
  
   public static function offerEdit($id, $currencys, $amount, $price, $lang, $offer_id, $private_key)
   {
        $data = array(
            'currency'        => $currencys,
            'amount'          => $amount,
            'price'           => $price,
            'offer_id'        => $offer_id,
            'private_key'     => $private_key,
            'lang'            => $lang
        );
      
        return QueryBuilder::table('website_shop_offers')->where('id', $id)->update($data);
    }
  
}