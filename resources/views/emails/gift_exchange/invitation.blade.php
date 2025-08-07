@component('mail::message')
# You're Invited to a Gift Exchange!

You have been invited to join the event: **{{ $event->name }}**

@if($event->description)
> {{ $event->description }}
@endif

**Event Deadline:** {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}

@if($event->budget_max)
**Budget Limit:** up to €{{ number_format($event->budget_max, 2) }}
@endif

Click the button below to accept or decline your invitation:

@component('mail::button', ['url' => $invitationLink])
View Invitation
@endcomponent

If you did not expect this invitation, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent