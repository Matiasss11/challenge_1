<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'price_per_night' => 'required|numeric|min:0',
        ];

        // Si el método es PATCH, permite que los campos sean opcionales
        if ($this->isMethod('patch')) {
            foreach ($rules as $key => $value) {
                $rules[$key] = str_replace('required', 'sometimes', $value);
            }
        }

        return $rules;
    }
}
