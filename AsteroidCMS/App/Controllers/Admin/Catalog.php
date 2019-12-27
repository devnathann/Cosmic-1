<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Models\Admin;

use Core\View;

use Library\Json;
use Library\HotelApi;

class Catalog
{
    private $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }
  
    public function request()
    {
        $validate = request()->validator->validate([
            'catid'       => 'required|numeric',
            'caption'     => 'required|max:50',
            'parent_id'   => 'required',
            'page_layout' => 'required',
            'visible'     => 'numeric|pattern:^(?:1OR0)$',
            'enabled'     => 'numeric|pattern:^(?:1OR0)$'
        ]);

        if (!$validate->isSuccess()) {
            echo '{"status":"error","message":"Fill in all fields!"}';
            exit;
        }
      
        $catid = input()->post('catid')->value;
        $caption = input()->post('caption')->value;
        $page_headline = input()->post('page_headline')->value;
        $page_teaser = input()->post('page_teaser')->value;
        $parent_id = input()->post('parent_id')->value;
        $page_layout = input()->post('page_layout')->value;
        $visible = input()->post('visible')->value;
        $enabled = input()->post('enabled')->value;
      
        $catalogue = Admin::getCatalogPagesById(input()->post('catid')->value);
      
        $query = Admin::updateCatalogPages($catid, $caption, $page_teaser, $page_headline, $parent_id, $page_layout, $visible, $enabled);
      
        if($query) {
            if(Config::apiEnabled) {
                HotelApi::execute('updatecatalog');
            }
        }
      
        echo '{"status":"success","message":"Item is successfully editted!"}';
        exit;
    }
  
    public function additem()
    {
        $validate = request()->validator->validate([
            'sprite_id'               => 'numeric',
            'item_name'               => 'required',
            'public_name'             => 'required',
            'width'                   => 'required',
            'length'                  => 'required',
            'stack_height'            => 'required',
            'page_id'                 => 'required|numeric',
            'allow_stack'             => 'required|pattern:^(?:1OR0)$',
            'allow_sit'               => 'required|pattern:^(?:1OR0)$',
            'allow_lay'               => 'required|pattern:^(?:1OR0)$',
            'allow_walk'              => 'required|pattern:^(?:1OR0)$',
            'allow_gift'              => 'required|pattern:^(?:1OR0)$', 
            'allow_trade'             => 'required|pattern:^(?:1OR0)$',
            'allow_recycle'           => 'required|pattern:^(?:1OR0)$',
            'allow_marketplace_sell'  => 'required|pattern:^(?:1OR0)$',
            'allow_inventory_stack'   => 'required|pattern:^(?:1OR0)$',
            'type'                    => 'required',
            'interaction_type'        => 'required',
            'interaction_modes_count' => 'required',
            'page_id'                 => 'required|numeric',
            'cost_credits'            => 'required|numeric',
            'cost_points'             => 'required|numeric',
            'points_type'             => 'required|numeric',
            'amount'                  => 'required|numeric',
            'limited_sells'           => 'required|numeric',
            'limited_stack'           => 'required|numeric',
        ]);

        if (!$validate->isSuccess()) {
            echo '{"status":"error","message":"Fill in all fields!"}';
            exit;
        }
          
        $furni_id = input()->post('furniture_id')->value ?? null;
      
        if($query = Admin::updateFurniture(array(
            'items_base' => array(
                'sprite_id'               => input()->post('sprite_id')->value,
                'item_name'               => input()->post('item_name')->value,
                'public_name'             => input()->post('public_name')->value,
                'width'                   => input()->post('width')->value,
                'length'                  => input()->post('length')->value,
                'stack_height'            => input()->post('stack_height')->value,
                'allow_stack'             => input()->post('allow_stack')->value,
                'allow_sit'               => input()->post('allow_sit')->value,
                'allow_lay'               => input()->post('allow_lay')->value,
                'allow_walk'              => input()->post('allow_walk')->value,
                'allow_gift'              => input()->post('allow_gift')->value,
                'allow_trade'             => input()->post('allow_trade')->value,
                'allow_recycle'           => input()->post('allow_recycle')->value,
                'allow_marketplace_sell'  => input()->post('allow_marketplace_sell')->value,
                'allow_inventory_stack'   => input()->post('allow_inventory_stack')->value,
                'type'                    => input()->post('type')->value,
                'interaction_type'        => input()->post('interaction_type')->value,
                'interaction_modes_count' => input()->post('interaction_modes_count')->value,
            ),
            'catalog_items' => array(
                'catalog_name'            => input()->post('catalog_name')->value,
                'cost_credits'            => input()->post('cost_credits')->value,
                'cost_points'             => input()->post('cost_points')->value,
                'points_type'             => input()->post('points_type')->value,
                'amount'                  => input()->post('amount')->value,
                'limited_sells'           => input()->post('limited_sells')->value,
                'limited_stack'           => input()->post('limited_stack')->value,
                'page_id'                 => input()->post('page_id')->value
            )
        ), $furni_id));

        HotelApi::execute('updatecatalog');

        echo '{"status":"success","message":"Item is successfully editted!"}';
        exit;
    }
  
    public function getFurnitureById()
    {
        $this->data->itemsids = Admin::getCatalogItemsByItemIds(input()->post('post')->value);
        $this->data->furniture = Admin::getFurnitureById(input()->post('post')->value);

        echo Json::raw($this->data);
    }
  
    public function getCatalogItemsByItemId()
    {
        $itemsids = Admin::getCatalogItemsByPageId(input()->post('id')->value);
        foreach($itemsids as $item) {
            $getCurrencys = array_flip(Config::currencys);
            $item->club_only = ($item->club_only == 0) ? 'No' : 'Yes';
            $item->cost_points = ($item->points_type != 0) ? $item->cost_points . ' (' . ucfirst($getCurrencys[$item->points_type]) . ')' : 0;
        }
      
        echo Json::filter($itemsids, 'desc', 'id');
    }
  
    public function getCatalogByPageId()
    {
        $this->data->page = Admin::getCatalogPagesById(input()->post('post')->value);
        if(isset($this->data->page->parent_id)) {
            $this->data->page->parent = Admin::getCatalogPagesById($this->data->page->parent_id);
        }
      
        $this->data->items = Admin::getCatalogItemsByPageId(input()->post('post')->value);
      
        echo Json::raw($this->data);
    }
  
    public function getCatalogPages()
    {
        $pages = Admin::getCatalogPages();
        if ($pages == null) {
            exit;
        }
      
        foreach ($pages as $row) {
            $row->page_texts = !empty($row->page_texts) ? $row->page_texts : 'Empty';
            $row->enabled = $row->enabled ? 'Yes' : 'No';
            $row->visible = $row->visible ? 'Yes' : 'No';
        }
        echo Json::raw($pages);
    }
  
    public function view()
    {
        View::renderTemplate('Admin/Management/catalog.html', ['permission' => 'housekeeping_server_catalog']);
    }
}