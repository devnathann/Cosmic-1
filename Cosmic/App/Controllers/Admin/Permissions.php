<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Core;
use App\Models\Permission;

use Core\View;

use Library\Json;

use stdClass;
use QueryBuilder;

class Permissions
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function getranks()
    {
        echo Json::encode(Admin::getRanks(true));
    }

    public function getpermissioncommands()
    {
        //* todo https://asteroidcms.nl/housekeeping/permissions/manage#
    }

    public function changepermissionrank()
    {
        $command_id = input()->post('command_id')->value;
        $minimum_rk = filter_var(input()->post('minimum_rank')->value, FILTER_SANITIZE_NUMBER_INT);

        if (Admin::changeMinimumRank($command_id, $minimum_rk)) {
            return Json::encode(["status" => "success", "message" => "Permission rank has been changed!"]);
        }
    }

    public function createrank()
    {     
        $commandsArray = json_decode(input()->post('value')->value);
        $permissionsArray = json_decode(input()->post('post')->value, true);

        foreach ($commandsArray as $key => $item) {
            if ($item->id == "fname") {
                $this->data->name = $item->value;
            } else {
                $obj = $item->id;
                if ($item->value === "on") {
                    $this->data->$obj = '0';
                } else {
                    $this->data->$obj = $item->value;
                }
            }
        }

        if (empty($this->data->rank_name)) {
            return Json::encode(["status" => "error", "message" => "Rank can not be empty!"]);
        }
      
        if (in_array($this->data->rank_name, array_column(Admin::getRanks(true), 'name'))) {
            return Json::encode(["status" => "error", "message" => "Rank name is already in use!"]);
        }
  
        Admin::addRank($this->data, $permissionsArray);
        return Json::encode(["status" => "success", "message" => "Rank added successfully!"]);
    }

    public function getwebsiteranks()
    {
        $this->data->ranks = Admin::getAllWebPermissions();
        Json::filter($this->data->ranks, 'desc', 'id');
    }

    public function edit()
    {
        $this->data->ranks = Admin::getRankById(input()->post('post')->value);
        echo Json::encode($this->data);
    }

    public function wizard()
    {
        $permission = Admin::getWebPermissions(input()->post('post')->value);
        echo Json::encode($permission);
    }

    public function getpermissions()
    {
        $this->data->permissions = Permission::get(input()->post('roleid')->value);
        Json::filter($this->data->permissions, 'desc', 'id');
    }

    public function search()
    {
        $permissionsById = Core::getField('permissions', 'id', 'id', input()->post('post')->value);
        if (empty($permissionsById)) {
            return Json::encode(["status" => "error", "message" => "There is an error occurred, please try again!"]);
        }

        return Json::encode(["status" => "success", "message" => "Permissions has been loaded!"]);
    }

    public function addpermission()
    {
        $role_id = input()->post('roleid')->value;
        $permission_id = input()->post('permissionid')->value;

        if (empty($role_id) || empty($permission_id)) {
            return Json::encode(["status" => "error", "message" => "Permission can not be added!"]);
        }

        $permissionExists = Core::getField('website_permissions', 'id', 'id', $permission_id);
        if (Admin::roleExists($role_id, $permission_id) || empty($permissionExists))  {
            return Json::encode(["status" => "error", "message" => "Permissions has already added to this role!"]);
        }

        Admin::createPermission($role_id, $permission_id);
        return Json::encode(["status" => "success", "message" => "Permissions has been added!"]);
    }

    public function delete()
    {
        $permissionId = Core::getField('website_permissions_ranks', 'id', 'id', input()->post('id')->value);
        if (empty($permissionId)) {
            return Json::encode(["status" => "error", "message" => "No permission found!"]);
        }

        Admin::deletePermission($permissionId);
        return Json::encode(["status" => "success", "message" => "Permissions has been deleted!"]);
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/permissions.html', ['permission' => 'housekeeping_permissions', 'permission_columns' => Permission::getAllColumns()]);
    }
}