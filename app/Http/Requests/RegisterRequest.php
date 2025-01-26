<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'string', 'max:15'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'address' => ['string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'healthid' => ['string', 'max:255'],
        ];
    }
}