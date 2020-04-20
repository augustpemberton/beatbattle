<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBattleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user()->id == $this->route('battle')->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $start_time = $this->route('battle')->start_time;
        $min_end_time = $start_time->add(10, 'minutes');
        return [
            'name'        => 'max:255',
            'description' => 'max:2000',
            'start_time'  => 'date|after:now',
            'end_time'    => 'date|after:'.$min_end_time,
            'voting_time' => 'date|date_difference_minutes:end_time,1'
        ];
    }

    public function messages()
    {
      return [
        'end_time.date_difference_minutes'    => 'End time must be at least 10 minutes after start time.',
        'voting_time.date_difference_minutes' => 'Voting period must be at least 1 minute.'
      ];
    }
}
