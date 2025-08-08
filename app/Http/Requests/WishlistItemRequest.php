<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'itemname' => 'required|string',
            'url'  => 'required|url',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|file|image|max:5120', // max 5MB
            'image' => 'nullable|file|image|max:5120', // also allow "image" field
            'user_wishlist_id' => 'nullable|exists:user_wishlists,id', // Keep for backward compatibility
            'user_wishlist_ids' => 'nullable|array|min:1',
            'user_wishlist_ids.*' => 'exists:user_wishlists,id',
        ];
    }
}