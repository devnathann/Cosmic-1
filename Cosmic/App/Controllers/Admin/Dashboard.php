<?php
namespace App\Controllers\Admin;

use App\Core;
use App\Models\Admin;

use Core\View;

use Library\Json;

class Dashboard
{
    public function latestplayers()
    {
        $latest_users = Admin::getLatestPlayers();
        if ($latest_users == null) {
            exit;
        }

        foreach ($latest_users as $row) {
            $row->last_login  = $row->online ? 'Online' : date("d-m-Y H:i:s", $row->last_login);
            $row->ip_current  = Core::convertIp($row->ip_current);
            $row->ip_register = Core::convertIp($row->ip_register);

            if (!\App\Models\Core::permission('housekeeping_change_email', request()->player->rank)) {
                $row->email = '';
            }
        }

        Json::filter($latest_users, 'desc', 'id');
    }

    public function latestnamechanges()
    {
        Json::filter(Admin::getNameChanges(), 'desc', 'id');
    }

    public function usersonline()
    {
        $online_users = Admin::getOnlinePlayers();

        foreach ($online_users as $row) {
            $row->ip = Core::convertIp($row->ip_register);
        }

        Json::filter($online_users, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/home.html', ['permission' => 'housekeeping']);
    }
}