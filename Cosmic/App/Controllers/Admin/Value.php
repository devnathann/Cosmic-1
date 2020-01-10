<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Core;
use App\Models\Admin;
use App\Models\Value as Values;

use Core\View;

use Library\Json;
use Library\HotelApi;

use stdClass;

class Value
{
    private $data;
  
    public function reloadCatalog() 
    {
        HotelApi::execute('updatecatalog');
        return Json::encode(["status" => "success", "message" => "Server catalog reloaded!"]);
    }

    public function editItem() 
    {
        $validate = request()->validator->validate([
            'id'            => 'required|numeric',
            'cost_points'   => 'required|numeric',
            'cost_credits'  => 'required|numeric',
            'points_type'   => 'required|numeric',
            'club_only'     => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
      
        $value_id = input()->post('id')->value;
        $cost_points = input()->post('cost_points')->value;
        $cost_credits = input()->post('cost_credits')->value;
        $points_type = input()->post('points_type')->value;
        $club_only = input()->post('club_only')->value;
      
        $value = Admin::getValueById($value_id);
        if(!$value) {
            return Json::encode(["status" => "error", "message" => "This item doesnt exist!"]);
        }
      
        Admin::editValueById($value_id, $cost_points, $cost_credits, $points_type, $club_only); 
        return Json::encode(["status" => "success", "message" => "Item successfuly edited!"]);
    }

    public function addcategory()
    {
        $validate = request()->validator->validate([
            'name'      => 'required|max:50',
            'cat_ids'   => 'required',
            'hidden'    => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }
  
        $cat_ids =  '[' . input()->post('cat_ids')->value . ']';
        $name = input()->post('name')->value;
        $hidden = input()->post('hidden')->value;

        Admin::addValueCategory($cat_ids, $name, $hidden, Core::convertSlug($name));
        return Json::encode(["status" => "success", "message" => "Category: {$name} is succesfully added!"]);
    }

    public function deleteCategory()
    {
        $category = Admin::getValueCategoryById(input()->post('post')->value);
        if (empty($category)) {
            return Json::encode(["status" => "error", "message" => "Category item doesnt exist!"]);
        }

        Admin::removeValueCategory($category->id);
        return Json::encode(["status" => "success", "message" => "Category is succesfully deleted!"]);
    }
  
    public function getValueById()
    {
        $value = Admin::getValueById(input()->post('post')->value);
        if(empty($value)) {
            exit;
        }
      
        $this->data = new stdClass();
        
        $this->data->currencys = Config::currencys;
        $this->data->value = $value;
      
        Json::encode($this->data);
    }
  
    public function getCategorys()
    {
        $values = Values::getValueCategorys();
      
        if(empty($values)) {
            exit;
        }
      
        foreach($values as $row) {
            foreach(json_decode($row->cat_ids) as $page_id) {
                $row->pages = isset($row->pages) ? $row->pages : null;
                $row->pages .= Admin::getCatalogPagesById($page_id)->caption . ', ';
            }
        }
      
        Json::filter($values, 'desc', 'id');
    }
  
    public function getCatalogItems()
    {
        $values = Values::getValueCategoryById(input()->post('id')->value);  
        $allItems = Values::getValues($values);
        Json::filter($allItems, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/value.html', [
            'permission' => 'housekeeping_website_rarevalue'
        ]);
    }
}