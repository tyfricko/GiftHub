<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftExchangeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $event = $this->route('event');

        return $user && $event && $user->id === $event->created_by;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'end_date' => 'required|date|after:now',
            'budget_max' => 'nullable|numeric|min:0',
            'requires_shipping_address' => 'nullable|boolean',
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
            'name.required' => 'The event name is required.',
            'name.max' => 'The event name cannot exceed 255 characters.',
            'end_date.required' => 'The end date is required.',
            'end_date.after' => 'The end date must be in the future.',
            'budget_max.numeric' => 'The budget must be a valid number.',
            'budget_max.min' => 'The budget must be at least 0.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to update this event.');
    }
}
