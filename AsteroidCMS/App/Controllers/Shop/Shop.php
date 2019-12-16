<?php
namespace App\Controllers\Shop;

use App\Config;
use Core\Locale;
use Core\View;

use stdClass;

class Shop
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function index($lang = Config::language)
    {
        $this->data->lang = $lang;
        $this->data->shop = \App\Models\Shop::getOffers($this->data->lang);

        View::renderTemplate('Shop/shop.html', [
            'title' => Locale::get('core/title/shop/index'),
            'page'  => 'shop',
            'data'  => $this->data
        ]);
    }
}