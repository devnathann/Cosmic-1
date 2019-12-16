<?php
namespace App\Models;

use QueryBuilder;

class Shop
{

    public static function getOffers($lang = 'NL')
    {
        return QueryBuilder::table('website_shop_offers')->where('lang', $lang)->get();
    }

    public static function getOfferById($id)
    {
        return QueryBuilder::table('website_shop_offers')->where('id', $id)->first();
    }

    public static function getOfferByOfferId($offer_id)
    {
        return QueryBuilder::table('website_shop_offers')->where('offer_id', $offer_id)->first();
    }
}