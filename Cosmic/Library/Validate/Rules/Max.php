<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Max extends Rule
{
    use Traits\SizeTrait;

    /** @var string */
    protected $message;

    /** @var array */
    protected $fillableParams = ['max'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . Locale::get('core/pattern/can_be') . ' :max ' . Locale::get('core/pattern/characters_long');
    }

    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $max = $this->getBytesSize($this->parameter('max'));
        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        return $valueSize <= $max;
    }
}
