<?php
namespace App\Controllers\Shop;

use App\Config;

use App\Models\Log;
use App\Models\Core;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\HotelApi;
use Library\Json;

use stdClass;

class Offers
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function offerAction($offer_id)
    {
        $offer = \App\Models\Shop::getOfferByOfferId($offer_id);
      
        if($offer) {
            $offer->currency = Core::getCurrencyByType($offer->currency)->currency;
        }
      
        return $this->data->offer = $offer;
    }

    public function validate()
    {
        $validate = request()->validator->validate([
            'offer_id'  => 'required',
            'code'      => 'required|min:4|max:8'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $offer_id = input()->post('offer_id')->value;
        $code = input()->post('code')->value;

        $offer = \App\Models\Shop::getOfferByOfferId($offer_id);
        if ($offer == null) {
            response()->json(["status" => "error", "message" => Locale::get('shop/offers/invalid_transaction')]);
        }

        $dedipass = file_get_contents('https://api.dedipass.com/v1/pay/?public_key=' . $offer_id . '&private_key=' . $offer->private_key . '&code=' . $code);
        $dedipass = json_decode($dedipass);
      
        if ($dedipass->status != 'success') {
            response()->json(["status" => "error", "message" => Locale::get('shop/offers/invalid_code')]);
        }
      
        $amount = Player::getCurrencys(request()->player->id)[$offer->currency_type]->amount;
  
        HotelApi::execute('givepoints', array('user_id' => request()->player->id, 'points' => $offer->amount, 'type' => $offer->currency));
        Log::addPurchaseLog(request()->player->id, $offer->amount . ' '.Locale::get('core/belcredits').' (' . $code . ')', $offer->lang);
        response()->json(["status" => "success", "message" => Locale::get('shop/offers/success_1').' ' . $offer->amount . ' '.Locale::get('shop/offers/success_2')]);
    }

    public function index($offerid)
    {
        $offer_id = $this->offerAction($offerid);

        if(!empty($offer_id))
        {
            View::renderTemplate('Shop/offers.html', [
                'title' => Locale::get('core/title/shop/index'),
                'page'  => 'shop_offers',
                'data'  => $this->data
            ]);
            exit;
        }

        redirect('/shop');
    }
}