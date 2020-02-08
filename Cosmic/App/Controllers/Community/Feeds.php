<?php
namespace App\Controllers\Community;

use App\Core;

use App\Controllers\Home\Profile;

use App\Models\Permission;
use App\Models\Community;
use App\Models\Player;

use Library\Json;

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
            return;
        }

        $reply      = input()->post('reply')->value;
        $user_id    = input()->post('userid')->value;

        $player = Player::getDataById($user_id, 'username');
        if ($player == null) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }

        if (empty($reply) || empty($user_id)) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }

        $userposts = Community::getFeedsByUserid($user_id);
        if(!empty($userposts)) {
            if(end($userposts)->from_user_id == request()->player->id){
                response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
            }
        }

        Community::addFeedToUser(Core::filterString(Core::tagByUser($reply)), request()->player->id, $user_id);
        response()->json(["status" => "success", "message" => Locale::get('core/notification/message_placed'), "replacepage" => "profile/" . $player->username]);

        //$object->feedid = $feed_id;
        //$object->username = $player->username;

        //Notification::add('feed', $object);

    }

    public function delete()
    {
        $feed_id = Community::getFeedsByFeedId(input()->post('feedid')->value);
        if($feed_id == null) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }

        if ($feed_id->to_user_id != request()->player->id && Permission::exists('housekeeping_moderation_tools', request()->player->rank)) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }

        Community::deleteFeedById($feed_id->id);
        response()->json(["status" => "error", "success" => Locale::get('core/notification/message_deleted'), "replacepage" => "profile/". Player::getDataById($feed_id->to_user_id, array('username'))->username]);
    }

    public function like()
    {
        $post = input()->post('post')->value;

        if (Community::userAlreadylikePost($post, request()->player->id)) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/already_liked')]);
        }

        Community::insertLike($post, request()->player->id);
        response()->json(["status" => "success", "message" => Locale::get('core/notification/liked')]);
    }

    public function more()
    {
        $feeds = new Profile();
        $init = $feeds->feeds(input()->post('count')->value, input()->post('player_id')->value);

        echo Json::encode(['feeds' => $init]);
    }
}
