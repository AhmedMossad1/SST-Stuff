<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'password' => 'required|min:8',
        ];
    }
}
