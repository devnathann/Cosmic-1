<?php
namespace App\Models;

use App\Config;
use App\Models\Admin;

use PDO;
use QueryBuilder;

class Value
{
    public static $allItems = array();
    public static $allSubItems = array();
  
    public static function getValueCategorys()
    {
        return QueryBuilder::table('website_rare_values')->get();
    }
  
    public static function getValueCategoryById($id)
    {
        return QueryBuilder::table('website_rare_values')->where('id', $id)->get();
    }
 
    public static function ifSubpageExists($id)
    {
        return QueryBuilder::query("SELECT id from catalog_pages WHERE parent_id = " . $id)->first();
    }
  
    public static function getCatalogItemsByParentId($id)
    {
        return QueryBuilder::query("SELECT id from catalog_pages WHERE parent_id IN ($id)")->get();
    }
  
    public static function getAllCatalogItems($page_id)
    {
        return QueryBuilder::query("SELECT DISTINCT catalog_items.*, (SELECT COUNT(*) FROM items WHERE item_id = catalog_items.id) AS amount FROM catalog_items WHERE page_id IN ($page_id)")->get();
    }
  
    public static function getValues($values, $transform = false)
    {
       foreach($values as $row) {

            /** Check if page has a subpage */
         
            foreach(json_decode($row->cat_ids) as $page_id) {
                (!empty(self::ifSubpageExists($page_id))) ? $pages[] = $page_id : $page[] = $page_id;
            }
         
           /**  Get all furni id's from page */
         
            if(!empty($page)) {
                self::$allItems = (self::getAllCatalogItems(join(',', array_map('intval', $page))));
            }
         
            /**  If page has a subpage get item id's */
         
            if(!empty($pages)) {
                $catalogSubpage = self::getCatalogItemsByParentId(join(',', array_map('intval', $pages)));
            }
          
            /** Get all furni id's from subpage */
         
            if(!empty($catalogSubpage)) {
                foreach($catalogSubpage as $items) {
                    $item[] = $items->id;
                }
                self::$allSubItems = self::getAllCatalogItems(join(',', array_map('intval', $item)));
            }
         
            /** Merge if subpage exists otherwise return page */
          
            $itemList = (!empty(self::$allSubItems)) ? array_merge_recursive(self::$allItems, self::$allSubItems) : self::$allItems;
 
            /** Get related item data */
            
            foreach($itemList as $item) {
                $getCurrencys = array_flip(Config::currencys);
              
                if($item->cost_points != 0 && !$transform) {
                    $item->cost_points = ($item->points_type != 0) ? $item->cost_points . ' (' . ucfirst($getCurrencys[$item->points_type]) . ')' : null;
                }
            }
        }
        return $itemList ?? null;
    }
}