<?php

namespace App\Rules;

use Composer\Semver\VersionParser;
use Illuminate\Contracts\Validation\Rule;
use Exception;

class Version implements Rule
{
    /**
     * @var \Composer\Semver\VersionParser
     */
    protected $parser;

    /**
     * Create a new rule instance.
     */
    public function __construct(VersionParser $parser)
    {
        $this->parser = $parser;
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
        try {
            $this->parser->normalize($value);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.semver');
    }
}
