<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Models\Ban;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\Json;

use stdClass;

use MaxMind\Db\Reader\InvalidDatabaseException;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class Vpn
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function ban()
    {
        $player = input()->post('id')->value;
        $reader = new Reader(__DIR__. Config::vpnLocation);

        try {

            $last_ip = Player::getDataByUsername($player, 'ip_current')->ip_current;
            $organisation = file_get_contents('https://api.ipdata.co/' . $last_ip . '?api-key=2b3dcf9260762a123c4d1ddeb9ae50c3d188ce34f1f93fe8241d4a5b');

            $record = $reader->asn($last_ip);

            $asn = Ban::getNetworkBanByAsn($record->autonomousSystemNumber);
            if ($asn != null) {
                echo '{"status":"error","message":"AS' . $asn->asn . ' is already banned."}';
                exit;
            }

            Ban::createNetworkBan($record->autonomousSystemNumber, json_decode($organisation)->asn->name, request()->player->id);
            echo '{"status":"success","message":"AS' . $record->autonomousSystemNumber . ' is added to our ban list"}';

        } catch (AddressNotFoundException $e) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '"}';
            exit;
        } catch (InvalidDatabaseException $e) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '"}';
            exit;
        }
    }

    public function delete()
    {
        $ban = Ban::getNetworkBanById(input()->post('asn')->value);
        if ($ban == null) {
            echo '{"status":"error","message":"AS' . $ban->asn . ' is not banned."}';
            exit;
        }

        Ban::removeNetworkBan($ban->asn);
        echo '{"status":"success","message":"AS' . $ban->asn . ' / ' . $ban->host . ' is deleted!"}';
    }

    public function getasnbans()
    {
        $asn = Ban::getNetworkBans();
        if ($asn) {
            foreach ($asn as $row) {
                $row->added_by = Player::getDataById($row->added_by, 'username')->username;
            }
        }

        Json::filter($asn, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/vpn.html', ['apiKey' => '2b3dcf9260762a123c4d1ddeb9ae50c3d188ce34f1f93fe8241d4a5b', 'permission' => 'housekeeping_vpn_control']);
    }
}