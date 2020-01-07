<?php
namespace App\Controllers\Community;

use App\Core;
use App\Models\Community;
use App\Models\Player;

use Core\Locale;
use Core\View;

use stdClass;

class Photos
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function like()
    {
        if (Community::userAlreadylikePhoto(input()->post('post'), request()->player->id)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/already_liked') . '"}';
            exit;
        }

        Community::insertPhotoLike(input()->post('post'), request()->player->id);
        echo '{"status":"success","message":"' . Locale::get('core/notification/liked') . '"}';
    }

    public function more()
    {
        $this->index(input()->post('offset')->value, true);
        echo response()->json(array('photos' => $this->data->photos));
    }

    public function index($offset = null, $request = false)
    {
        if(is_array($offset)) {
            $photos = Community::getPhotos(12);
        } else {
            $photos = Community::getPhotos(12, $offset);
        }

        foreach($photos as $photo) {
            $user = Player::getDataById($photo->user_id, array('username','look'));

            $photo->author =  $user->username;
            $photo->figure =  $user->look;

            $photo->likes = Community::getPhotosLikes($photo->id);
        }

        $this->data->photos = $photos;

        if($request == false)
            View::renderTemplate('Community/photos.html', [
                'title' => Locale::get('core/title/community/photos'),
                'page' => 'community_photos',
                'data' => $photos
            ]);
    }
}