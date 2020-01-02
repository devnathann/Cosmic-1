<?php
namespace App\Controllers\Community;

use App\Config;
use App\Models\Value as Values;
use App\Models\Core;

use Core\Locale;
use Core\View;

class Value
{
    public function index($slug = null, $cat_id = 1)
    {
        if (!empty($slug)) {
            $cat_id = explode('-', $slug)[0];
        }

        $values = Values::getValueCategoryById($cat_id);
        if(empty($values)) {
            redirect('/');
        }  
      
        $allItems = Values::getValues($values, true);
        $categories = Values::getValueCategorys();

        View::renderTemplate('Community/value.html', [
            'title'           => Locale::get('core/title/community/value'),
            'page'            => 'community_value',
            'values'          => $allItems,
            'cat'             => $values[0] ?? $cat_id,
            'categories'      => $categories,
            'currency_types'  => Config::currencys
        ]);
    }
}