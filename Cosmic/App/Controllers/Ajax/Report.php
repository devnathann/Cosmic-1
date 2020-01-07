<?php
namespace App\Controllers\Ajax;

use App\Models\Community;
use App\Models\Permission;
use Core\Locale;

class Report
{
    public function feed()
    {
        $validate = request()->validator->validate([
            'itemId'   => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $itemId = input()->post('itemId')->value;

        $item = Community::getFeedsByFeedId($itemId);
        if ($item) {
            if (in_array('housekeeping_moderation_tools', array_column(Permission::get(request()->player->rank), 'permission'))) {
                Community::deleteFeedById($itemId);
                \App\Models\Report::remove($itemId, 'feed');

                echo '{"status":"success","message":"'.Locale::get('core/notification/invisible').'"}';
                exit;
            }

            $report = \App\Models\Report::getByItemId($itemId, 'feed');

            if($report != null) {
                echo '{"status":"success","message":"'.Locale::get('core/notification/staff_received').'"}';
                exit;
            }

            \App\Models\Report::insert($itemId, 'feed', $item->from_user_id, request()->player->id);
            echo '{"status":"success","message":"'.Locale::get('core/notification/staff_received').'"}';
        }
    }

    public function post()
    {
        $validate = request()->validator->validate([
            'itemId'   => 'required|numeric'
        ]);
      
        if(!$validate->isSuccess()) {
            exit;
        }
      
        \App\Controllers\Community\Forum::report(input()->post('itemId')->value);
    }
}