<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id'
        ];
    }
}

