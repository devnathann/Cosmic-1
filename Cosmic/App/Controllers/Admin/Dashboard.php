<?php
namespace App\Controllers\Admin;

use App\Core;

use App\Models\Permission;
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

            if (!Permission::exists('housekeeping_change_email', request()->player->rank)) {
                $row->mail = '';
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
  
    public function maintenance()
    {
        if (!Permission::exists('housekeeping_permissions', request()->player->rank)) {
            return Json::encode(["status" => "error", "message" => "You have no permissions to do this!"]);
        }
      
        $maintenance = Admin::saveSettings('maintenance', (\App\Models\Core::settings()->maintenance == "1") ? "0" : "1");
        return Json::encode(["status" => "success", "message" => "Maintenance updated"]);
    }

    public function view()
    {
        View::renderTemplate('Admin/home.html', ['permission' => 'housekeeping', 'version' => \App\Controllers\Api::version()]);
    }
}