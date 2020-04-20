<?php

namespace App\Http\Requests;
use Carbon\Carbon;

use Illuminate\Foundation\Http\FormRequest;

class CreateBattleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $min_start = Carbon::now()->subtract(1, 'minutes');
        return [
            'name'        => 'required|max:255',
            'description' => 'max:2000',
            'samples'     => 'required',
            'samples.*'   => 'required|mimes:mpga|max:50000',
            'start_time'  => 'date|after:'.$min_start,
            'end_time'    => 'date|date_difference_minutes:start_time,10',
            'voting_time' => 'date|date_difference_minutes:end_time,1'
        ];
    }

    public function messages()
    {
      return [
        'start_time.after'                    => 'Start time cannot be in the past.',
        'end_time.date_difference_minutes'    => 'End time must be at least 10 minutes after start time.',
        'voting_time.date_difference_minutes' => 'Voting period must be at least 1 minute.'
      ];
    }
}
