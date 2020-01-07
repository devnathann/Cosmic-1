<?php
namespace App\Controllers\Shop;

use App\Models\Player;

use Core\Locale;
use Core\View;

class History
{
    public function index()
    {
        $history = Player::getPurchases(request()->player->id);

        View::renderTemplate('Shop/history.html', [
            'title'     => Locale::get('core/title/shop/history'),
            'page'      => 'shop_history',
            'history'   => $history
        ]);
    }
}