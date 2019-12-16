<?php

namespace Library\Validate\Rules;

use Core\Locale;
use Library\Validate\Rule;

class Required extends Rule
{
    use Traits\FileTrait;

    /** @var bool */
    protected $implicit = true;

    /** @var string */
    protected $message;

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = Locale::get('core/pattern/is_required');
    }

    public function check($value): bool
    {
        $this->setAttributeAsRequired();

        if ($this->attribute and $this->attribute->hasRule('uploaded_file')) {
            return $this->isValueFromUploadedFiles($value) and $value['error'] != UPLOAD_ERR_NO_FILE;
        }

        if (is_string($value)) {
            return mb_strlen(trim($value), 'UTF-8') > 0;
        }
        if (is_array($value)) {
            return count($value) > 0;
        }
        return !is_null($value);
    }

    /**
     * Set attribute is required if $this->attribute is true
     *
     * @return void
     */
    protected function setAttributeAsRequired()
    {
        if ($this->attribute) {
            $this->attribute->setRequired(true);
        }
    }
}
