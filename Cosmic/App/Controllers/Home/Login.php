<?php
namespace App\Controllers\Home;

use App\Auth;

use App\Config;
use App\Core;
use App\Hash;
use App\Models\Player;

use Core\Locale;
use Core\Session;

use Core\View;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Login
{
    private $auth;

    public function logout()
    {
        Auth::logout();
        redirect('/');
    }

    public function request()
    {
        $validate = request()->validator->validate([
            'username' => 'required|min:1|max:30',
            'password' => 'required|min:1|max:100',
            'pincode'  => 'max:6'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
        
        $username = input()->post('username')->value;
        $password = input()->post('password')->value;
        $pin_code =  !empty(input()->post('pincode')->value) ? input()->post('pincode')->value : false;
      
        $player = Player::getDataByUsername($username, array('id', 'password', 'rank', 'secret_key'));
        if ($player == null || !Hash::verify($player->id, $password, $player->password)) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_password') . '"}';
            exit;
        }

        /*
        *  Verification authentication
        */

        if(!$pin_code) {
            if (!is_null($player->secret_key)) {
                echo '{"status":"pincode_required"}';
                exit;
            }
        }
      
        if ($pin_code && $player->secret_key == null) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_pincode') . '"}';
            exit;
        }
        
        if($player->secret_key != null) {
            $this->googleAuthentication($pin_code, $player->secret_key);
        }
      
        /*
        *  End authentication
        */

        $this->login($player);
    }

    protected function login($user)
    {
        if ($user && Auth::login($user)) {
            echo '{"status":"success","message":"","location":"/home"}';
        } else {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_password') . '"}';
        }
    }

    protected function googleAuthentication($pin_code, $secret_key)
    {
        $this->auth = new GoogleAuthenticator();

        if (!$this->auth->checkCode($secret_key, $pin_code)) {
            echo '{"status":"error","message":"' . Locale::get('login/invalid_pincode') . '","close_popup":"loginpin"}';
            exit;
        }

        return true;
    }

    public function facebook()
    {
        try {
            $fb = new Facebook([
                'app_id' => Config::appId,
                'app_secret' => Config::appSecret,
                'default_graph_version' => 'v2.10',
            ]);
        } catch (FacebookSDKException $e) {
            echo '{"status":"error","message":"API request can not be executed"}';
        }

        $helper = $fb->getRedirectLoginHelper();

        if (Session::exists('facebook_access_token')) {
            $accessToken = Session::get('facebook_access_token');
        } else {
            $accessToken = $helper->getAccessToken();
        }

        if (!isset($accessToken)) {
            header('Location: ' . $helper->getLoginUrl(Config::path . '/facebook', array('email')));
            exit;
        }

        if (Session::exists('facebook_access_token')) {
            $fb->setDefaultAccessToken(Session::get('facebook_access_token'));
        } else {
            Session::set('facebook_access_token', (string)$accessToken);

            $oAuth2Client = $fb->getOAuth2Client();
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken(Session::get('facebook_access_token'));
            Session::set('facebook_access_token', (string)$longLivedAccessToken);
            $fb->setDefaultAccessToken(Session::get('facebook_access_token'));
        }

        $profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
        $profile = $profile_request->getGraphNode()->asArray();

        $player = Player::getDataByFbId($profile['id'], array('id', 'rank'));
        if ($player == null) {
            $data = new \stdClass();

            $data->username = Core::filterCharacters($profile['firstname']).'-'.rand(10000, 99999);

            $player = Player::getDataByUsername($data->username, 'id');
            if($player != null) {
                $data->username = Core::filterCharacters($profile['firstname']).'-'.rand(10000, 99999);

                $player = Player::getDataByUsername($data->username, 'id');
                if($player != null) {
                    $data->username = Core::filterCharacters($profile['firstname']).'-'.rand(10000, 99999);
                }
            }

            $data->password         = 'FB-'.sha1($profile['email'].Config::SECRET_TOKEN);
            $data->email            = $profile['email'];
            $data->figure           = Config::look[rand(1,9)];
            $data->gender           = 'M';
            $data->birthdate_day    = 1;
            $data->birthdate_month  = 1;
            $data->birthdate_year   = 2004;

            if (!Player::create($data)) {
                echo '{"status":"error","message":"' . Locale::get('core/notification/something_wrong') . '"}';
                exit;
            }
        }

        if (Auth::login($player)) {
            redirect('/');
        }
    }
}