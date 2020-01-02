<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Core;
use App\Models\Admin;
use App\Models\Value as Values;

use Core\View;

use Library\Json;

use stdClass;

class Value
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function add()
    {
        $validate = request()->validator->validate([
            'name'      => 'required|max:50',
            'swf'       => 'required|max:50',
            'category'  => 'required|numeric',
            'cost_bc'   => 'required|max:10|numeric',
            'cost_ss'   => 'required|max:10|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $name = input()->post('name')->value;
        $swf = input()->post('swf')->value;
        $category = input()->post('category')->value;
        $price_bc = input()->post('cost_bc')->value;
        $price_ss = input()->post('cost_ss')->value;

        $catId = Admin::getValuesByCategory($category);
        if (empty($catId)) {
            echo '{"status":"success","message":"No category found"}';
            exit;
        }

        $item = Admin::getValueBySwf($swf);
        if (empty($item)) {
            Admin::addValue($name, $swf, $category, $price_bc, $price_ss, 'none', request()->player->id);
            echo '{"status":"success","message":"Furni: ' . $swf . ' is succesfully added!"}';
            exit;
        }

        $values = \App\Models\Value::getRareValuesById($item->id);
        $rateValue = ($price_bc > $values->price_bc ? 'up' : 'down');

        Admin::editValue($name, $swf, $category, $price_bc, $price_ss, $rateValue, request()->player->id);
        echo '{"status":"success","message":"Furni value is updated!"}';
    }

    public function addcategory()
    {
        $validate = request()->validator->validate([
            'name'      => 'required|max:50',
            'cat_ids'   => 'required',
            'hidden'    => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
  
        $cat_ids =  '[' . input()->post('cat_ids')->value . ']';
        $name = input()->post('name')->value;
        $hidden = input()->post('hidden')->value;

        Admin::addValueCategory($cat_ids, $name, $hidden, Core::convertSlug($name));
        echo '{"status":"success","message":"Category: ' . $name . ' is succesfully added."}';
    }

    public function deleteCategory()
    {
        $category = Admin::getValueCategoryById(input()->post('post')->value);
        if (empty($category)) {
            echo '{"status":"error","message":"Category does not exists!"}';
            exit;
        }

        Admin::removeValueCategory($category->id);
        echo '{"status":"success","message":"Category is succesfully deleted!"}';
    }
  
    public function getValueById()
    {
        $value = Admin::getValueById(input()->post('post')->value);
        if(empty($value)) {
            exit;
        }
        
        $this->data->currencys = Config::currencys;
        $this->data->value = $value;
      
        Json::raw($this->data);
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
  
    public function getCatalogItems($loop = true)
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