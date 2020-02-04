<?php
namespace App;

use App\Config;

use App\Models\Permission;
use App\Models\Player;

use Jenssegers\Date\Date;

use stdClass;

class Core
{
    public static function filterString($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    /*
     * TODO: make this better for facebook register(?)
     */
    public static function filterCharacters($getString)
    {
        $getCharacters = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
        );

        return strtolower(strtr($getString, $getCharacters));
    }

    public static function getIpAddress()
    {
        $ipAddress = (isset($_SERVER['HTTP_CDN_LOOP']) == "cloudflare") ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        return filter_var($ipAddress, FILTER_VALIDATE_IP) ? $ipAddress : false;
    }

    public static function convertIp($ip_address)
    {
        if(!Permission::exists('housekeeping_ip_display', request()->player->rank)) {
            $regex = array("/[\d]{3}$/", "/[\d]{2}$/", "/[\d]$/");
            $replace = array("xxx", "xxx", "xxx");
            return preg_replace($regex, $replace, $ip_address);
        } else {
            return $ip_address;
        }
    }

    public static function convertSlug($string)
    {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $string);
        $slug = trim(preg_replace('~[^-\w]+~', '', $slug), '-');
        return strtolower(preg_replace('~-+~', '-', $slug));
    }

    public static function timediff($timestamp, $type = null)
    {
        Date::setLocale(Config::language);
        $convert = ($timestamp - time());
        $date = new Date(time() - $convert, Config::region);

        return $type == null ? $date->ago() : $date->timespan();
    }

    public static function tagByUser($message)
    {
        $data = new stdClass();

        preg_match_all('/@(\w+)/', $message, $match);

        foreach($match[1] as $row) {
            $user = Player::getDataByUsername($row, array('id','username'));
            if(isset($user->id) != null) {
                $data->user[$user->id]['userid'] = $user->id;
                $userProfile = '@[url='. Config::path . '/profile/'.$row.']' .$row . '[/url]';
                $message = str_replace("@" . $row, $userProfile, $message);
            }
        }

        return $message;
    }


}