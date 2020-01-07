<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Same extends Rule
{

    /** @var string */
    protected $message;

    /** @var array */
    protected $fillableParams = ['field'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */


    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/not_same') . ' met :field!';
    }

    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $field = $this->parameter('field');
        $anotherValue = $this->getAttribute()->getValue($field);

        return $value == $anotherValue;
    }
}
