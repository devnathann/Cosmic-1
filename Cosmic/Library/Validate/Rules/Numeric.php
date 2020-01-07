<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Numeric extends Rule
{

    /** @var string */
    protected $message = "The :attribute must be numeric";

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/numeric');
    }

    public function check($value): bool
    {
        return is_numeric($value);
    }
}
