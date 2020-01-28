<?php
namespace App\Controllers\Community;

use App\Models\Permission;
use App\Models\Player;

use Core\Locale;
use Core\View;

class Staff
{
    public function index()
    {
        $ranks = Permission::getRanks();

        foreach ($ranks as $row) {
          
            if(!Permission::exists('website_invisible_staff', $row->id)) {
                $row->users = Player::getDataByRank($row->id);

                if (!empty($row->users) && is_array($row->users)) {
                    foreach ($row->users as $users) {
                        $users->settings = Player::getSettings($users->id);
                    }
                }
            }
        }

        View::renderTemplate('Community/staff.html', [
            'title' => Locale::get('core/title/community/staff'),
            'page'  => 'community_staff',
            'data'  => $ranks
        ]);
    }
}