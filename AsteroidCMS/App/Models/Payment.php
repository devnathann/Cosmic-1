<?php
namespace App\Models;

use PDO;
use QueryBuilder;

class Payment
{
    public static function getByOrderId($order_id)
    {
        return QueryBuilder::table('website_shop_orders')->where('order_id', $order_id)->first();
    }

    public static function createOrder($order_id, $player_id)
    {
        $data = array(
            'order_id'  => $order_id,
            'player_id' => $player_id,
            'timestamp' => time()
        );

        return QueryBuilder::table('website_shop_orders')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function update($order_id, $key, $value){
        return QueryBuilder::table('website_shop_orders')->where('order_id', $order_id)->update(array($key => $value));
    }
}