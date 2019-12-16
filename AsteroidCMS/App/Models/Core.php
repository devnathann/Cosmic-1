<?php
namespace App\Models;

use QueryBuilder;

class Core
{
    public static function getField($table, $data, $id, $player_id)
    {
        $query = QueryBuilder::table($table)->select($data)->where($id, $player_id)->first();
        return $query->$data ?? null;
    }

    public static function permission($permission, $player_rank)
    {
        if (in_array($permission, array_column(Permission::get($player_rank), 'permission'))) {
            return true;
        }

        return false;
    }

    public static function getWebsitePage($select) {
        return QueryBuilder::table('website_pages')->select('website_pages.*')->select('website_pages_categories.name')
                    ->join('website_pages_categories', 'website_pages.category', '=', 'website_pages_categories.id')
                    ->where('website_pages.action', $select)->first();
    }

    public static function getWebsiteConfig($select)
    {
        $query = QueryBuilder::table('website_config')->select($select)->first();
        return $query->$select;
    }

    public static function getOnlineCount()
    {
        return QueryBuilder::table('users')->where('online', "1")->count();
    }
}