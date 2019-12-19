<?php
namespace App\Controllers\Community;

use App\Controllers\Home\Profile;
use App\Core;
use App\Models\Permission;

use App\Models\Community;
use App\Models\Player;

use Core\Locale;

use stdClass;

class Feeds
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function post()
    {
        $validate = request()->validator->validate([
            'reply'     => 'required|max:50',
            'userid'    => 'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $reply      = input()->post('reply')->value;
        $user_id    = input()->post('userid')->value;

        $player = Player::getDataById($user_id, 'username');
        if ($player == null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }

        if (empty($reply) || empty($user_id)) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }
      
        $userposts = Community::getFeedsByUserid($user_id);
        if(!empty($userposts)) {
            if(end($userposts)->from_user_id == request()->player->id){
                echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
                exit;
            }
        }

        Community::addFeedToUser(Core::filterString(Core::tagByUser($reply)), request()->player->id, $user_id);

        //$object->feedid = $feed_id;
        //$object->username = $player->username;

        //Notification::add('feed', $object);

        echo '{"status":"success","message":"' . Locale::get('core/notification/message_placed') . '","replacepage":"profile/' . $player->username . '"}';
    }

    public function delete()
    {
        $feed_id = Community::getFeedsByFeedId(input()->post('feedid')->value);
        if($feed_id == null) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }

        if ($feed_id->to_user_id != request()->player->id && !in_array('housekeeping_moderation_tools', array_column(Permission::get(request()->player->rank), 'permission'))) {
            echo '{"status":"error","message":"'.Locale::get('core/notification/something_wrong').'"}';
            exit;
        }

        Community::deleteFeedById($feed_id->id);

        echo '{"status":"success","message":"' . Locale::get('core/notification/message_deleted') . '","replacepage":"profile/'. Player::getDataById($feed_id->to_user_id, array('username'))->username .'"}';
    }

    public function like()
    {
        $post = input()->post('post')->value;

        if (Community::userAlreadylikePost($post, request()->player->id)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/already_liked') . '"}';
            exit;
        }

        Community::insertLike($post, request()->player->id);
        echo '{"status":"success","message":"' . Locale::get('core/notification/liked') . '"}';
    }

    public function more()
    {
        $feeds = new Profile();
        $init = $feeds->feeds(input()->post('count')->value, input()->post('player_id')->value);

        echo json_encode(array('feeds' => $init));
    }
}