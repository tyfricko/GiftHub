@component('mail::message')
# Your Gift Exchange Assignment!

You are participating in the event: **{{ $event->name }}**

**Event Deadline:** {{ \Carbon\Carbon::parse($event->end_date)->toDayDateTimeString() }}

---

## 🎁 Recipient

# {{ $recipient->user->username ?? $recipient->user->email }}

@if(!empty($recipientProfileUrl))
[View their profile and public wishlists]({{ $recipientProfileUrl }})
@endif

---

## 🎯 Suggested gift (based on their wishlist and event budget)

@if(!empty($suggestedItem))
@component('mail::panel')
@if(!empty($suggestedItem['image_url']))
<img src="{{ $suggestedItem['image_url'] }}" alt="{{ $suggestedItem['title'] }}" style="max-width:120px;float:right;margin-left:12px" />
@endif
**{{ $suggestedItem['title'] }}**

@if(!empty($suggestedItem['price']))
Price: {{ $suggestedItem['price'] }} {{ $suggestedItem['currency'] ?? '' }}
@endif

@if(!empty($suggestedItem['url']))
[View item]({{ $suggestedItem['url'] }})
@endif
@endcomponent
@else
@if(isset($recipient->user) && method_exists($recipient->user, 'wishlistItems') && $recipient->user->wishlistItems()->exists())
### Other wishlist items
@foreach($recipient->user->wishlistItems()->latest()->take(5)->get() as $wish)
- @if($wish->url)<a href="{{ $wish->url }}">{{ $wish->itemname ?? $wish->title }}</a>@else{{ $wish->itemname ?? $wish->title }}@endif
@endforeach
@else
_No wishlist items found for this user._
@endif
@endif

Please keep your assignment secret and follow the event's budget guidelines.

Thanks,<br>
{{ config('app.name') }}
@endcomponent