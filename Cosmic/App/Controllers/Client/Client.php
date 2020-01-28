<?php
namespace App\Controllers\Client;

use App\Core;
use App\Config;
use App\Token;

use App\Models\Api;
use App\Models\Ban;
use App\Models\Player;
use App\Models\Room;

use Core\Locale;
use Core\View;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use stdClass;

class Client
{
    private $data;

    public function client()
    {
        $this->data = new stdClass();

        $reader = new Reader(__DIR__. Config::vpnLocation);

        try {
            $record = $reader->asn(Core::getIpAddress());
        } catch (AddressNotFoundException $e) {
        } catch (InvalidDatabaseException $e) {

        }

        $asn = Ban::getNetworkBanByAsn($record->autonomousSystemNumber);

        if ($asn) {
            View::renderTemplate('Client/vpn.html', ['asn' => $asn->asn, 'type' => 'vpn']);
            exit;
        }

        $OS = substr($_SERVER['HTTP_USER_AGENT'], -2);
        if (strpos($_SERVER['HTTP_USER_AGENT'], "Puffin") !== false && ($OS == "WD" || $OS == "LD" || $OS == "MD")) {
            View::renderTemplate('Client/vpn.html', ['type' => 'puffin']);
            exit;
        }

        $this->data->auth_ticket = Token::authTicket(request()->player->id);
        $this->data->unique_id = sha1(request()->player->id . '-' . time());

        Player::update(request()->player->id, ['auth_ticket' => $this->data->auth_ticket]);

        if(isset($_GET['room'])) {
            if(is_numeric($_GET['room'])) {
                $room = Room::getById($_GET['room']);

                if($room != null) {
                    $this->data->room = $room->id;
                }
            }
        }

        View::renderTemplate('Client/client.html', [
            'title' => Locale::get('core/title/hotel'),
            'data'  => $this->data
        ]);
    }

    public function hotel()
    {
        View::renderTemplate('Home/home.html', [
            'title' => Locale::get('core/title/hotel'),
            'page'  => 'hotel'
        ]);
    }

    public function count()
    {
        echo \App\Models\Core::getOnlineCount();
        exit;
    }
}