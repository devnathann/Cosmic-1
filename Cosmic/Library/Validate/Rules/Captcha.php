<?php
namespace Library\Validate\Rules;

use App\Config;

use App\Models\Core;

use Core\Locale;
use Library\Validate\Rule;

class Captcha extends Rule
{
    /** @var string */
    protected $message;

    /**
     * Check $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct()
    {
        $this->message = Locale::get('core/pattern/captcha');
    }

    public function check($value): bool
    {
        return $this->captcha($value);
    }

    public function captcha($value) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'secret' => Core::settings()->recaptcha_secretkey ?? null,
            'response' => $value,
            'remoteip' => request()->getIp()
        ));
        $curlData = curl_exec($curl);
        curl_close($curl);
        $recaptcha = json_decode($curlData, true);
        if ($recaptcha["success"]) {
            return true;
        }
        return false;
    }
}