<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * Create a new rule instance.
     */
    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->hash) {
            return false;
        }

        return resolve('hash')->check($value, $this->hash);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.hash');
    }
}
