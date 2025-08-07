@component('mail::message')
# Your Gift Exchange Assignment!

You are participating in the event: **{{ $event->name }}**

**Event Deadline:** {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}

---

## 🎁 You will be giving a gift to:

# {{ $recipient->user->username ?? $recipient->user->email }}

---

@if(isset($recipient->user->wishes) && $recipient->user->wishes->count())
### Gift Suggestions from their Wishlist:
@foreach($recipient->user->wishes as $wish)
- {{ $wish->title }}
@endforeach
@else
_No wishlist items found for this user._
@endif

Please keep your assignment secret and follow the event's budget guidelines.

Thanks,<br>
{{ config('app.name') }}
@endcomponent