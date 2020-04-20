<?php

namespace App\Http\Requests;

use Log;
use App\Vote;
use App\Entry;
use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
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
        return [
            'entry_id' => 'required|exists:entries,id|max_votes:' . $this->user()->id,
        ];
    }
}