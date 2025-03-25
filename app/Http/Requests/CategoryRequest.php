<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }    

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:categories,name|regex:/^[a-zA-Z0-9\s\-]+$/u',
        ];
    }
}
