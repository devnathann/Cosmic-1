<?php
namespace App\Controllers\Shop;

use App\Config;

use App\Models\Player;
use App\Models\Core;

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

    public function index()
    {
        $this->data->shop = \App\Models\Shop::getOffers();
      
        foreach($this->data->shop as $offers) {
            $offers->currency = Core::getCurrencyByType($offers->currency)->currency;
        }
      
        $this->data->currencys = Player::getCurrencys(request()->player->id);

        View::renderTemplate('Shop/shop.html', [
            'title' => Locale::get('core/title/shop/index'),
            'page'  => 'shop',
            'data'  => $this->data
        ]);
    }
}