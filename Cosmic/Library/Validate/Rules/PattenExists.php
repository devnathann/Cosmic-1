<?php
namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class PatternExists extends Rule
{
    /** @var string */
    protected $message;

    protected $fillableParams = ['patternexists'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/invalid_characters');
    }

    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);
        $regex = '/^(' . $this->parameter('pattern') . ')$/u';
        return !preg_match($regex, $value);
    }
}
