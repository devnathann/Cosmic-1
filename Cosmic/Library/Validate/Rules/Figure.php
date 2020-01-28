<?php
namespace Library\Validate\Rules;

use App\Config;
use Core\Locale;

use Library\Validate\Rule;

class Figure extends Rule
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
        $this->message = Locale::get('core/notification/something_wrong');
    }

    public function check($value): bool
    {
        return $this->figure($value);
    }

    public function figure($value) {
        if(in_array(substr($value, strrpos($value, 'hr-')), Config::look['male']) ? substr($value, strrpos($value, 'hr-')) : Config::look['male'][rand(1, 9)]) {
            return true;
        }

        return false;
    }
}