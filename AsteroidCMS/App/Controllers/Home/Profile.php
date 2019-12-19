<?php
namespace App\Controllers\Home;

use App\Config;
use App\Core;

use App\Models\Community;
use App\Models\Player;

use Core\Locale;
use Core\View;

use stdClass;

class Profile
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function profile($username = null)
    {
        if($username == null) {
            redirect('/');
            exit;
        }

        $player = Player::getDataByUsername($username);
        if($player == null) {
            redirect('/');
            exit;
        }

        $this->data->player = $player;
        $this->data->player->last_online = Core::timediff($this->data->player->last_online);
        $this->data->player->settings = Player::getSettings($player->id);

        $this->data->player->badges = Player::getBadges($player->id);
        $this->data->player->friends = Player::getFriends($player->id);

        $this->data->player->groups = Player::getGroups($player->id);
        $this->data->player->rooms = Player::getRooms($player->id);
        $this->data->player->photos = Player::getPhotos($player->id);

        $this->data->player->badgeCount = count($this->data->player->badges);
        $this->data->player->friendCount = count($this->data->player->friends);
        $this->data->player->groupCount = count($this->data->player->groups);
        $this->data->player->roomCount = count($this->data->player->rooms);
        $this->data->player->photoCount = count($this->data->player->photos);

        foreach ($this->data->player->photos as $row) {
            $row->timestamp = Core::timediff($row->timestamp);
        }

        $this->data->player->feeds = Community::getFeedsByUserid($player->id);
        $this->data->player->feedCount = count($this->data->player->feeds);
        $this->data->player->feedCountTotal = count($this->data->player->feeds);

        foreach ($this->data->player->feeds as $row) {
            $row->likes = Community::getLikes($row->id);
        }

        $this->template();
    }

    public function feeds($offset = null, $user_id = null)
    {
        $feeds = Community::getFeedsByUserIdOffset($offset, $user_id, 6);

        foreach ($feeds as $row) {
            $from_user = Player::getDataById($row->from_user_id, array('username','look'));
            $row->from_username = $from_user->username;
            $row->figure = $from_user->look ?? null;
            $row->likes = Community::getLikes($row->id);
        }

        return $feeds;
    }

    public function search()
    {
        if(!Player::exists(input()->post('search')->value)) {
            echo '{"status":"error", "message":"'.Locale::get('core/notification/profile_notfound').'"}';
            exit;
        }

        echo '{"replacepage":"profile/'.input()->post('search')->value.'"}';
    }

    public function template()
    {
        View::renderTemplate('Home/profile.html', [
            'title' => $this->data->player->username,
            'page'  => 'profile',
            'data'  => $this->data
        ]);
    }
}
