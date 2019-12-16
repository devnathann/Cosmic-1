<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Min extends Rule
{
    use Traits\SizeTrait;

    /** @var string */
    protected $message;

    /** @var array */
    protected $fillableParams = ['min'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/must_be') . ' :min ' . Locale::get('core/pattern/characters_long');
    }

    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $min = $this->getBytesSize($this->parameter('min'));
        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        return $valueSize >= $min;
    }
}
