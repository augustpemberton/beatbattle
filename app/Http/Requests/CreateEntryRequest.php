<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEntryRequest extends FormRequest
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
      // TODO can only vote in voting period
        return [
            'notes'             => 'max:1000',
            'samples'           => 'required|array|between:1,1',
            'battle_id'         => 'required|exists:battles,id|unique:entries,battle_id,NULL,id,user_id,'.$this->user()->id,
            'listenable_early'  => 'boolean',
        ];
    }
}
