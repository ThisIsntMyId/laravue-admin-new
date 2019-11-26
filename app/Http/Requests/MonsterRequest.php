<?php

namespace App\Http\Requests;

use App\Laravue\Models\Food;
use App\Laravue\Models\Location;
use App\Laravue\Models\Nature;
use Illuminate\Foundation\Http\FormRequest;

class MonsterRequest extends FormRequest
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
            'name' => 'required',
            'japanese_name' => 'required',
            'description' => 'nullable',
            'japanese_description' => 'nullable',
            'fav_food' => ['required', function ($attribute, $value, $fail) {
                $ids = explode(',', $value);
                foreach ($ids as $id) {
                    if (!(Food::where('id', $id)->exists())) {
                        $fail('Food with id ' . $attribute . ' is not present in db');
                    }
                }
            }],
            'nature' => ['required', function ($attribute, $value, $fail) {
                $ids = explode(',', $value);
                foreach ($ids as $id) {
                    if (!(Nature::where('id', $id)->exists())) {
                        $fail('Nature with id ' . $attribute . ' is not present in db');
                    }
                }
            }],
            'health' => 'required|integer|max:1000|min:0',
            'energy' => 'required|integer|max:1000|min:0',
            'found_in' => ['required', function ($attribute, $value, $fail) {
                $ids = explode(',', $value);
                foreach ($ids as $id) {
                    if (!(Location::where('id', $id)->exists())) {
                        $fail('Location with id ' . $attribute . ' is not present in db');
                    }
                }
            }],
        ];
    }
}
