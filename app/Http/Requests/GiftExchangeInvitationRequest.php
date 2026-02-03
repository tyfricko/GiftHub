<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftExchangeInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $eventId = $this->route('event') ?? $this->route('eventId');

        // Only event owner can invite participants
        if (! $user || ! $eventId) {
            return false;
        }

        // Ensure we have the model, not just the ID
        $event = $eventId instanceof \App\Models\GiftExchangeEvent
            ? $eventId
            : \App\Models\GiftExchangeEvent::find($eventId);

        return $event && $user->id === $event->created_by;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
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
            'emails.required' => 'At least one email address is required.',
            'emails.array' => 'The emails must be provided as a list.',
            'emails.min' => 'At least one email address is required.',
            'emails.*.required' => 'Each email address is required.',
            'emails.*.email' => 'Each entry must be a valid email address.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to invite participants to this event.');
    }
}
