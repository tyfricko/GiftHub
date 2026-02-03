<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $wish = $this->route('wish') ?? $this->route('id');

        return $wish && $this->user() && $this->user()->id === $wish->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'image_url' => 'nullable|url',
            'user_wishlist_id' => 'nullable|exists:user_wishlists,id',
            'sort_order' => 'nullable|integer|min:0',
            'user_wishlist_ids' => 'nullable|array',
            'user_wishlist_ids.*' => 'exists:user_wishlists,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'The title cannot exceed 255 characters.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'currency.max' => 'The currency code cannot exceed 10 characters.',
            'image_url.url' => 'The image URL must be a valid URL.',
            'user_wishlist_id.exists' => 'The selected wishlist does not exist.',
            'sort_order.integer' => 'The sort order must be a number.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to update this wishlist item.');
    }
}
