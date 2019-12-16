<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Email extends Rule
{

    /** @var string */
    protected $message;

    /**
     * Check $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/email');
    }

    public function check($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
