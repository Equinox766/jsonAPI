<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    protected string $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if($this->hasUnderscores($value)) {
            $this->message = trans('validation.no_underscores');
            return false;
        }
        if($this->startWithDash($value)) {
            $this->message = trans('validation.no_starting_dashes');
            return false;
        }
        if($this->endWithDash($value)) {
            $this->message = trans('validation.no_ending_dashes');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @param mixed $value
     * @return false|int
     */
    protected function hasUnderscores(mixed $value): int|false
    {
        return preg_match('/_/', $value);
    }

    /**
     * @param mixed $value
     * @return false|int
     */
    protected function startWithDash(mixed $value): int|false
    {
        return preg_match('/^-/', $value);
    }

    /**
     * @param mixed $value
     * @return false|int
     */
    protected function endWithDash(mixed $value): int|false
    {
        return preg_match('/-$/', $value);
    }
}
