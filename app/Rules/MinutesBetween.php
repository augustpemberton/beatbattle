<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinutesBetween implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($d1_name, $date, $minutes)
    {
        $this->d1_name = $d1_name;
        $this->minutes = $minutes;
        $this->date = $date;
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
        $d1 = Carbon::parse($this->date);
        $d2 = Carbon::parse($value);
        return ($d1->diffInMinutes >= $this->minutes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be at least '.$minutes.' minutes after ' . $d1_name;
    }
}
