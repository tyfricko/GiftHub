# GiftHub Shipping Address Feature Specification

## 1. Architecture Analysis

### Current System Integration Points
- **Models**: [`GiftExchangeEvent`](app/Models/GiftExchangeEvent.php) and [`GiftExchangeParticipant`](app/Models/GiftExchangeParticipant.php)
- **Controller**: [`GiftExchangeController`](app/Http/Controllers/GiftExchangeController.php) handles event lifecycle
- **Views**: [`gift-exchange/show.blade.php`](resources/views/gift-exchange/show.blade.php) displays event details
- **Banner Pattern**: [`verification-banner.blade.php`](resources/views/components/ui/verification-banner.blade.php) for notifications

### Key Integration Points
1. **Event Creation**: Add shipping requirement toggle in event creation form
2. **Invitation Acceptance**: Collect shipping address during join flow
3. **Matching Logic**: Enforce address completion before assignment
4. **Event Display**: Show completion status and persistent reminders

## 2. Database Schema Design

### Migration 1: Add Shipping Requirement to Events
```php
// 2025_08_26_add_shipping_requirement_to_gift_exchange_events.php
Schema::table('gift_exchange_events', function (Blueprint $table) {
    $table->boolean('requires_shipping_address')->default(false)->after('budget_max');
});
```

### Migration 2: Add Shipping Address to Participants
```php
// 2025_08_26_add_shipping_address_to_gift_exchange_participants.php
Schema::table('gift_exchange_participants', function (Blueprint $table) {
    $table->json('shipping_address')->nullable()->after('joined_at');
    $table->timestamp('shipping_address_completed_at')->nullable()->after('shipping_address');
});
```

### Shipping Address JSON Structure
```json
{
  "full_name": "John Doe",
  "address_line_1": "123 Main Street",
  "address_line_2": "Apt 4B",
  "city": "Ljubljana",
  "state_province": "Ljubljana",
  "postal_code": "1000",
  "country": "Slovenia",
  "phone": "+386 1 234 5678",
  "delivery_notes": "Ring doorbell twice"
}
```

## 3. Event-Level Shipping Address Requirement

### Model Updates
```php
// app/Models/GiftExchangeEvent.php
protected $fillable = [
    'name', 'description', 'end_date', 'budget_max', 'created_by',
    'requires_shipping_address'  // Add this
];

protected $casts = [
    'requires_shipping_address' => 'boolean',
];

// Helper method
public function requiresShippingAddress(): bool
{
    return $this->requires_shipping_address;
}
```

### Form Validation
```php
// In GiftExchangeController::createEventWeb()
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'end_date' => 'required|date|after:now',
    'budget_max' => 'nullable|numeric|min:0',
    'requires_shipping_address' => 'boolean',  // Add this
]);
```

## 4. Participant Shipping Address Collection Flow

### Model Updates
```php
// app/Models/GiftExchangeParticipant.php
protected $fillable = [
    'event_id', 'user_id', 'status', 'joined_at',
    'shipping_address', 'shipping_address_completed_at'  // Add these
];

protected $casts = [
    'shipping_address' => 'array',
    'shipping_address_completed_at' => 'datetime',
];

// Helper methods
public function hasShippingAddress(): bool
{
    return !empty($this->shipping_address) && 
           !empty($this->shipping_address['full_name']) &&
           !empty($this->shipping_address['address_line_1']) &&
           !empty($this->shipping_address['city']) &&
           !empty($this->shipping_address['postal_code']) &&
           !empty($this->shipping_address['country']);
}

public function needsShippingAddress(): bool
{
    return $this->event->requires_shipping_address && !$this->hasShippingAddress();
}
```

### Collection Flow
1. **On Invitation Accept**: Check if event requires shipping address
2. **Redirect to Form**: If missing, redirect to shipping address collection
3. **Persistent Reminder**: Show banner until completed
4. **Block Matching**: Prevent assignment until all required addresses collected

## 5. Enforcement Rules

### Participation Rules
- Users can view event details without shipping address
- Users cannot be marked "ready for matching" without required shipping address
- Event owner cannot run matching until all participants have required addresses

### Matching Prevention
```php
// In GiftExchangeController::assignGifts()
if ($event->requires_shipping_address) {
    $participantsWithoutAddress = $participants->filter(function($p) {
        return !$p->hasShippingAddress();
    });
    
    if ($participantsWithoutAddress->count() > 0) {
        return response()->json([
            'error' => 'Cannot assign gifts. ' . $participantsWithoutAddress->count() . 
                      ' participants still need to provide shipping addresses.'
        ], 400);
    }
}
```

### Progress Tracking
```php
// Helper method for event completion status
public function getShippingAddressProgress(): array
{
    if (!$this->requires_shipping_address) {
        return ['required' => false];
    }
    
    $participants = $this->participants()->where('status', 'accepted')->get();
    $completed = $participants->filter(fn($p) => $p->hasShippingAddress())->count();
    
    return [
        'required' => true,
        'total' => $participants->count(),
        'completed' => $completed,
        'percentage' => $participants->count() > 0 ? round(($completed / $participants->count()) * 100) : 0
    ];
}
```

## 6. UI Components Design

### Shipping Address Banner Component
```php
// resources/views/components/ui/shipping-address-banner.blade.php
@props(['participant', 'event'])

@if($participant && $participant->needsShippingAddress())
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm p-4 md:p-6 mb-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-start space-x-3">
        <div class="text-blue-600 text-2xl mt-1">📦</div>
        <div>
          <h3 class="text-lg font-semibold text-blue-800">Shipping Address Required</h3>
          <p class="text-blue-700 mt-1">This event requires a shipping address for gift delivery. Please provide your address to participate in matching.</p>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-2 shrink-0">
        <a href="{{ route('gift-exchange.shipping-address', ['event' => $event->id]) }}" class="inline-block">
          <x-ui.button variant="primary" size="sm">Add Shipping Address</x-ui.button>
        </a>
      </div>
    </div>
  </div>
</div>
@endif
```

### Shipping Address Form Component
```php
// resources/views/components/ui/shipping-address-form.blade.php
@props(['participant' => null, 'event'])

<x-ui.card>
  <form method="POST" action="{{ route('gift-exchange.update-shipping-address', ['event' => $event->id]) }}" class="space-y-4">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <x-ui.form-group label="Full Name" required>
        <x-ui.input 
          name="full_name" 
          value="{{ old('full_name', $participant?->shipping_address['full_name'] ?? '') }}" 
          required 
        />
      </x-ui.form-group>
      
      <x-ui.form-group label="Phone" optional>
        <x-ui.input 
          name="phone" 
          type="tel"
          value="{{ old('phone', $participant?->shipping_address['phone'] ?? '') }}" 
        />
      </x-ui.form-group>
    </div>
    
    <x-ui.form-group label="Address Line 1" required>
      <x-ui.input 
        name="address_line_1" 
        value="{{ old('address_line_1', $participant?->shipping_address['address_line_1'] ?? '') }}" 
        required 
      />
    </x-ui.form-group>
    
    <x-ui.form-group label="Address Line 2" optional>
      <x-ui.input 
        name="address_line_2" 
        value="{{ old('address_line_2', $participant?->shipping_address['address_line_2'] ?? '') }}" 
      />
    </x-ui.form-group>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <x-ui.form-group label="City" required>
        <x-ui.input 
          name="city" 
          value="{{ old('city', $participant?->shipping_address['city'] ?? '') }}" 
          required 
        />
      </x-ui.form-group>
      
      <x-ui.form-group label="State/Province" required>
        <x-ui.input 
          name="state_province" 
          value="{{ old('state_province', $participant?->shipping_address['state_province'] ?? '') }}" 
          required 
        />
      </x-ui.form-group>
      
      <x-ui.form-group label="Postal Code" required>
        <x-ui.input 
          name="postal_code" 
          value="{{ old('postal_code', $participant?->shipping_address['postal_code'] ?? '') }}" 
          required 
        />
      </x-ui.form-group>
    </div>
    
    <x-ui.form-group label="Country" required>
      <select name="country" class="w-full border rounded p-2" required>
        <option value="">Select Country</option>
        <option value="Slovenia" {{ old('country', $participant?->shipping_address['country'] ?? '') === 'Slovenia' ? 'selected' : '' }}>Slovenia</option>
        <option value="Croatia" {{ old('country', $participant?->shipping_address['country'] ?? '') === 'Croatia' ? 'selected' : '' }}>Croatia</option>
        <option value="Austria" {{ old('country', $participant?->shipping_address['country'] ?? '') === 'Austria' ? 'selected' : '' }}>Austria</option>
        <option value="Italy" {{ old('country', $participant?->shipping_address['country'] ?? '') === 'Italy' ? 'selected' : '' }}>Italy</option>
        <!-- Add more countries as needed -->
      </select>
    </x-ui.form-group>
    
    <x-ui.form-group label="Delivery Notes" optional>
      <textarea 
        name="delivery_notes" 
        class="w-full border rounded p-2" 
        rows="3"
        placeholder="Special delivery instructions (optional)"
      >{{ old('delivery_notes', $participant?->shipping_address['delivery_notes'] ?? '') }}</textarea>
    </x-ui.form-group>
    
    <div class="flex justify-end gap-2">
      <x-ui.button as="a" href="{{ route('gift-exchange.show', $event->id) }}" variant="secondary">
        Cancel
      </x-ui.button>
      <x-ui.button type="submit" variant="primary">
        Save Shipping Address
      </x-ui.button>
    </div>
  </form>
</x-ui.card>
```

### Progress Indicator Component
```php
// resources/views/components/ui/shipping-progress.blade.php
@props(['event'])

@php
$progress = $event->getShippingAddressProgress();
@endphp

@if($progress['required'])
<x-ui.card class="mb-4">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="font-medium text-gray-900">Shipping Address Collection</h3>
      <p class="text-sm text-gray-600">{{ $progress['completed'] }} of {{ $progress['total'] }} participants completed</p>
    </div>
    <div class="text-right">
      <div class="text-2xl font-bold text-primary-600">{{ $progress['percentage'] }}%</div>
      <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $progress['percentage'] }}%"></div>
      </div>
    </div>
  </div>
  
  @if($progress['completed'] < $progress['total'])
    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
      <p class="text-sm text-yellow-800">
        <i class="fa fa-exclamation-triangle mr-1"></i>
        Cannot assign gifts until all participants provide shipping addresses.
      </p>
    </div>
  @endif
</x-ui.card>
@endif
```

## 7. Validation Rules and Data Privacy

### Validation Rules
```php
// app/Http/Requests/ShippingAddressRequest.php
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
        'phone' => 'nullable|string|max:20',
        'delivery_notes' => 'nullable|string|max:500',
    ];
}
```

### Privacy Considerations
1. **Access Control**: Only assigned gifter sees recipient's address after matching
2. **Data Retention**: Auto-delete addresses 60 days after event ends
3. **Encryption**: Store sensitive data encrypted in JSON column
4. **Audit Trail**: Log address access for security
5. **User Control**: Allow users to update/delete their addresses

### Data Retention Policy
```php
// app/Console/Commands/CleanupShippingAddresses.php
public function handle()
{
    $cutoffDate = now()->subDays(60);
    
    GiftExchangeParticipant::whereHas('event', function($query) use ($cutoffDate) {
        $query->where('end_date', '<', $cutoffDate);
    })
    ->whereNotNull('shipping_address')
    ->update([
        'shipping_address' => null,
        'shipping_address_completed_at' => null
    ]);
}
```

## 8. Implementation Plan

### Phase 1: Database and Models (1-2 days)
1. Create migration for event shipping requirement
2. Create migration for participant shipping address
3. Update model fillable arrays and casts
4. Add helper methods to models

### Phase 2: Controller Logic (2-3 days)
1. Update event creation to handle shipping requirement
2. Add shipping address collection routes and methods
3. Update invitation acceptance flow
4. Modify matching logic to enforce address completion

### Phase 3: UI Components (2-3 days)
1. Create shipping address banner component
2. Create shipping address form component
3. Create progress indicator component
4. Update event creation form
5. Update event show page

### Phase 4: Integration and Testing (1-2 days)
1. Integrate components into existing views
2. Add route definitions
3. Test complete flow
4. Add validation and error handling

### Migration Strategy
- **Backward Compatible**: New columns are nullable/default false
- **Gradual Rollout**: Existing events continue without shipping requirement
- **Data Migration**: No existing data needs migration
- **Feature Flag**: Can be enabled per event

### Route Additions
```php
// routes/web.php - Add to gift exchange group
Route::get('/gift-exchange/{event}/shipping-address', [GiftExchangeController::class, 'showShippingAddressForm'])
    ->name('gift-exchange.shipping-address');
Route::put('/gift-exchange/{event}/shipping-address', [GiftExchangeController::class, 'updateShippingAddress'])
    ->name('gift-exchange.update-shipping-address');
```

## 9. Success Metrics

### User Experience
- Shipping address completion rate > 90%
- Time to complete address form < 2 minutes
- User satisfaction with address collection flow

### System Performance
- No impact on existing event creation/management
- Address data properly encrypted and secured
- Successful matching with address enforcement

### Business Value
- Enables physical gift exchanges
- Improves gift delivery success rate
- Maintains user privacy and data security

---

This specification provides a comprehensive plan for implementing shipping address collection in the GiftHub gift exchange system while maintaining security, privacy, and user experience standards.