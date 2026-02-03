<?php

namespace App\Http\Requests;

use App\Enums\WishlistVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserWishlistRequest extends FormRequest
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
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_wishlists')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($this->userWishlist),
            ],
            'description' => 'nullable|string|max:1000',
            'visibility' => ['required', Rule::in(array_column(WishlistVisibility::cases(), 'value'))],
        ];

        return $rules;
    }
}
