<?php
namespace App\Controllers\Settings;

use App\Config;
use App\Models\Player;

use Core\Locale;
use Core\View;

use Library\HotelApi;

class Preferences
{
    public function validate()
    {
        $inArray = array(
            'allow_friend_requests',
            'allow_friend_alerts',
            'hide_home',
            'hide_online',
            'hide_last_online',
            'hide_staff',
            'allow_trade',
            'allow_mimic',
            'allow_follow',
            'allow_whisper'
        );

        $column = input()->post('post')->value;
        $type   = (int)filter_var(input()->post('type')->value, FILTER_VALIDATE_BOOLEAN);

        if (!is_int($type) || !in_array($column, $inArray)) {
            echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '","captcha_error":"error"}';
            exit;
        }

        Player::updateSettings(request()->player->id, $column, $type);

        if (Config::apiEnabled && request()->player->online) {
            HotelApi::player(request()->player->id, 'reload');
        }

        echo '{"status":"success","message":"' . Locale::get('settings/preferences_saved') . '"}';
    }

    public function index()
    {
        View::renderTemplate('Settings/preferences.html', [
            'title' => Locale::get('core/title/settings/index'),
            'page'  => 'settings_preferences',
            'data'  => Player::getSettings(request()->player->id)
        ]);
    }
}