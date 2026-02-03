<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftExchangeShippingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $event = $this->route('event');

        if (! $user || ! $event) {
            return false;
        }

        // Ensure event requires shipping address
        if (! method_exists($event, 'requiresShippingAddress') || ! $event->requiresShippingAddress()) {
            return false;
        }

        // Ensure user is an accepted participant for this event
        $participant = $event->participants()->where('user_id', $user->id)->first();

        return $participant && $participant->status === 'accepted';
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        // Trim string inputs
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = trim($value);
            }
        }

        // Normalize phone: allow +, numbers, spaces, dashes, parentheses
        if (! empty($input['phone'])) {
            $normalized = preg_replace('/[^0-9+\-\s\(\)]/', '', $input['phone']);
            $input['phone'] = $normalized;
        }

        $this->merge($input);
    }

    /**
     * Validation rules for shipping address.
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state_province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]{6,20}$/'],
            'delivery_notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number may contain only digits, spaces, +, dashes and parentheses and must be 6-20 characters.',
        ];
    }
}
