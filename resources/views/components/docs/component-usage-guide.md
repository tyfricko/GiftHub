# GiftHub Component Library - Usage Guide

## Overview

This guide documents all available UI components in the GiftHub component library. All components support dark mode and follow WCAG 2.1 AA accessibility standards.

## Core Components

### Button (`x-ui.button`)

Primary button component with multiple variants and sizes.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `type` | string | `'button'` | HTML button type attribute |
| `variant` | `'primary' \| 'secondary' \| 'danger' \| 'ghost'` | `'primary'` | Button visual style |
| `size` | `'sm' \| 'md' \| 'lg'` | `'md'` | Button size |
| `disabled` | boolean | `false` | Disable the button |
| `ariaLabel` | string \| null | `null` | ARIA label for accessibility |
| `loading` | boolean | `false` | Show loading spinner |
| `as` | `'button' \| 'a'` | `'button'` | Render as button or anchor |
| `href` | string \| null | `null` | URL for anchor links |

#### Usage Examples

```blade
<!-- Primary button -->
<x-ui.button>Save Changes</x-ui.button>

<!-- Secondary button -->
<x-ui.button variant="secondary">Cancel</x-ui.button>

<!-- Danger button -->
<x-ui.button variant="danger">Delete</x-ui.button>

<!-- Ghost button -->
<x-ui.button variant="ghost">Learn More</x-ui.button>

<!-- Small button -->
<x-ui.button size="sm">Small</x-ui.button>

<!-- Large button -->
<x-ui.button size="lg">Large</x-ui.button>

<!-- Loading state -->
<x-ui.button loading>Submitting...</x-ui.button>

<!-- As link -->
<x-ui.button as="a" href="/home">Go Home</x-ui.button>

<!-- With custom ARIA label -->
<x-ui.button ariaLabel="Submit form">Submit</x-ui.button>

<!-- Disabled -->
<x-ui.button disabled>Cannot Click</x-ui.button>
```

#### Accessibility Notes

- Buttons have proper focus states with 2px ring
- Loading state is announced to screen readers via ARIA attributes
- When using `as="a"`, the component automatically includes `role="button"`
- Use `ariaLabel` when button content is an icon only

---

### Input (`x-ui.input`)

Text input field with validation states and dark mode support.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `type` | string | `'text'` | Input type attribute |
| `name` | string \| null | `null` | Input name attribute |
| `id` | string \| null | `null` | Input ID (defaults to name if not provided) |
| `value` | string \| null | `null` | Input value |
| `placeholder` | string | `''` | Placeholder text |
| `disabled` | boolean | `false` | Disable the input |
| `required` | boolean | `false` | Mark as required |
| `autocomplete` | string \| null | `null` | Autocomplete attribute |
| `error` | boolean | `false` | Show error state styling |

#### Usage Examples

```blade
<!-- Basic input -->
<x-ui.input name="username" placeholder="Enter username" />

<!-- With ID -->
<x-ui.input id="user-email" name="email" type="email" placeholder="Enter email" />

<!-- Password input -->
<x-ui.input name="password" type="password" autocomplete="current-password" />

<!-- Required -->
<x-ui.input name="full-name" placeholder="Full Name" required />

<!-- With value -->
<x-ui.input name="city" value="Ljubljana" placeholder="City" />

<!-- Error state -->
<x-ui.input name="username" error />

<!-- Disabled -->
<x-ui.input name="read-only-field" value="Cannot edit" disabled />
```

#### Accessibility Notes

- Error state has visible red border and focus ring
- All inputs have visible focus states (2px ring)
- Use `id` prop and connect to label via `for` attribute
- Error messages should be linked via `aria-describedby`

---

### Form Group (`x-ui.form-group`)

Wrapper for form controls with label, error, and helper text.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `label` | string \| null | `null` | Label text |
| `for` | string \| null | `null` | ID of the associated form control |
| `error` | string \| null | `null` | Error message text |
| `help` | string \| null | `null` | Helper text |

#### Usage Examples

```blade
<!-- Basic usage -->
<x-ui.form-group label="Email Address" for="email">
    <x-ui.input name="email" id="email" type="email" />
</x-ui.form-group>

<!-- With error -->
<x-ui.form-group label="Password" for="password" error="Password is required">
    <x-ui.input name="password" id="password" type="password" />
</x-ui.form-group>

<!-- With help text -->
<x-ui.form-group label="Username" for="username" help="Must be at least 3 characters">
    <x-ui.input name="username" id="username" />
</x-ui.form-group>

<!-- Combined -->
<x-ui.form-group 
    label="Confirm Password" 
    for="password-confirm"
    help="Must match your password"
    error="Passwords do not match"
>
    <x-ui.input name="password_confirm" id="password-confirm" type="password" />
</x-ui.form-group>
```

#### Accessibility Notes

- Labels are automatically linked to inputs via `for` attribute
- Error messages have `role="alert"` and `aria-live="polite"` for screen readers
- Helper text uses low contrast for visual hierarchy

---

### Card (`x-ui.card`)

Card container with multiple variants for different use cases.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `title` | string \| null | `null` | Card title |
| `variant` | `'basic' \| 'elevated' \| 'interactive'` | `'basic'` | Card visual style |
| `class` | string | `''` | Additional CSS classes |

#### Usage Examples

```blade
<!-- Basic card -->
<x-ui.card>
    <p>This is the card content.</p>
</x-ui.card>

<!-- With title -->
<x-ui.card title="Welcome">
    <p>Welcome to GiftHub!</p>
</x-ui.card>

<!-- Elevated variant -->
<x-ui.card variant="elevated" title="Important">
    <p>This card has a stronger shadow.</p>
</x-ui.card>

<!-- Interactive variant -->
<x-ui.card variant="interactive" title="Click Me">
    <p>This card is clickable.</p>
</x-ui.card>

<!-- With custom classes -->
<x-ui.card title="Custom Card" class="border-primary-500">
    <p>This has custom styling.</p>
</x-ui.card>
```

#### Accessibility Notes

- Interactive variant includes `cursor-pointer` and hover effects
- All cards have proper border radius (12px / rounded-xl)
- Focus states work when cards are made interactive

---

### Alert (`x-ui.alert`)

Alert component for displaying messages with semantic variants.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `type` | `'success' \| 'error' \| 'warning' \| 'info'` | `'success'` | Alert type |
| `dismissible` | boolean | `false` | Show dismiss button |
| `title` | string \| null | `null` | Alert title |

#### Usage Examples

```blade
<!-- Success alert -->
<x-ui.alert>
    Changes saved successfully!
</x-ui.alert>

<!-- With title -->
<x-ui.alert title="Success!">
    Your account has been created.
</x-ui.alert>

<!-- Error alert -->
<x-ui.alert type="error">
    Something went wrong. Please try again.
</x-ui.alert>

<!-- Warning alert -->
<x-ui.alert type="warning">
    Your session will expire in 5 minutes.
</x-ui.alert>

<!-- Info alert -->
<x-ui.alert type="info">
    New features are available!
</x-ui.alert>

<!-- Dismissible -->
<x-ui.alert dismissible type="error">
    Connection failed. Dismiss to continue.
</x-ui.alert>
```

#### Accessibility Notes

- Has `role="alert"` and `aria-live="polite"` for screen readers
- Includes appropriate icons for each alert type
- Dismissible button has proper `aria-label`

---

### Tabs (`x-ui.tabs`)

Tab navigation component with active state indicators.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `tabs` | array | `[]` | Array of tab definitions |
| `active` | string \| null | `null` | Key of currently active tab |

#### Tab Definition Format

Each tab should be an array with:
- `key` (required): Unique identifier
- `label` (required): Display text
- `url` (optional): Link href
- `badge` (optional): Number for badge

#### Usage Examples

```blade
<x-ui.tabs 
    :tabs="[
        ['key' => 'overview', 'label' => 'Overview', 'url' => '/profile'],
        ['key' => 'wishlist', 'label' => 'Wishlist', 'url' => '/profile/wishlist'],
        ['key' => 'events', 'label' => 'Events', 'url' => '/profile/events', 'badge' => 3],
    ]" 
    active="wishlist"
/>
```

#### Accessibility Notes

- Tabs have `role="tablist"` and `role="tab"` attributes
- `aria-selected` indicates active state
- Keyboard navigation works via native browser behavior
- Active tab has visual border-bottom indicator

---

### Avatar (`x-ui.avatar`)

User avatar component with multiple sizes and fallback support.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `src` | string | `'/fallback-avatar.jpg'` | Image source |
| `alt` | string | `'User Avatar'` | Alt text for accessibility |
| `size` | `'xs' \| 'sm' \| 'md' \| 'lg' \| 'xl'` | `'md'` | Avatar size |
| `fallback` | string \| null | `null` | Fallback image path |

#### Usage Examples

```blade
<!-- Medium (default) -->
<x-ui.avatar src="/user-photos/john.jpg" alt="John's avatar" />

<!-- Small -->
<x-ui.avatar size="sm" src="/user-photos/jane.jpg" alt="Jane's avatar" />

<!-- Large -->
<x-ui.avatar size="lg" src="/user-photos/bob.jpg" alt="Bob's avatar" />

<!-- Extra small -->
<x-ui.avatar size="xs" src="/user-photos/alice.jpg" alt="Alice's avatar" />

<!-- Extra large -->
<x-ui.avatar size="xl" src="/user-photos/charlie.jpg" alt="Charlie's avatar" />

<!-- With custom fallback -->
<x-ui.avatar src="/missing.jpg" fallback="/custom-fallback.jpg" alt="User" />

<!-- Using default fallback -->
<x-ui.avatar alt="Unknown User" />
```

#### Accessibility Notes

- Images have `loading="lazy"` for performance
- Fallback images load on error via JavaScript
- Uses `<picture>` element for dark-mode specific fallbacks
- Always provide meaningful `alt` text

---

### Select (`x-ui.select`)

Dropdown select component with arrow icon.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `name` | string \| null | `null` | Select name attribute |
| `id` | string \| null | `null` | Select ID (defaults to name) |
| `selected` | string \| null | `null` | Currently selected value |
| `placeholder` | string | `'Select an option'` | Placeholder option text |
| `disabled` | boolean | `false` | Disable the select |
| `required` | boolean | `false` | Mark as required |
| `options` | array | `[]` | Array of options |

#### Options Format

Each option can be:
- A string (value and label are the same)
- An array with `value` and `label` keys

#### Usage Examples

```blade
<!-- Simple options -->
<x-ui.select 
    name="country" 
    :options="['Slovenia', 'Croatia', 'Austria']" 
    selected="Slovenia"
/>

<!-- Array of arrays -->
<x-ui.select 
    name="city" 
    :options="[
        ['value' => 'lj', 'label' => 'Ljubljana'],
        ['value' => 'mb', 'label' => 'Maribor'],
        ['value' => 'ce', 'label' => 'Celje'],
    ]"
    selected="lj"
    placeholder="Choose city"
/>

<!-- Required with validation -->
<x-ui.select 
    name="gift-category" 
    :options="['Electronics', 'Books', 'Clothing']"
    required
    error
/>

<!-- Disabled -->
<x-ui.select 
    name="legacy-field" 
    :options="['Option A', 'Option B']"
    disabled
/>
```

#### Accessibility Notes

- Custom arrow icon is decorative (`aria-hidden`)
- Has visible focus states
- Placeholder is a disabled option
- Use with form-group for proper labeling

---

### Textarea (`x-ui.textarea`)

Multi-line text input with resize disabled.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `name` | string \| null | `null` | Textarea name attribute |
| `id` | string \| null | `null` | Textarea ID (defaults to name) |
| `value` | string \| null | `null` | Textarea value |
| `placeholder` | string | `''` | Placeholder text |
| `disabled` | boolean | `false` | Disable the textarea |
| `required` | boolean | `false` | Mark as required |
| `rows` | number | `4` | Number of visible rows |
| `error` | boolean | `false` | Show error state styling |

#### Usage Examples

```blade
<!-- Basic usage -->
<x-ui.textarea 
    name="message" 
    placeholder="Enter your message" 
    rows="5"
/>

<!-- With value -->
<x-ui.textarea 
    name="description" 
    :value="$item->description" 
    placeholder="Item description"
/>

<!-- Error state -->
<x-ui.textarea 
    name="notes" 
    error
    placeholder="Additional notes"
/>

<!-- Minimal size -->
<x-ui.textarea 
    name="short-note" 
    rows="2"
    placeholder="Short note"
/>
```

#### Accessibility Notes

- Has visible focus states (2px ring)
- Error state has visual red styling
- Resize is disabled for consistent UI
- Use with form-group for proper labeling

---

### Checkbox (`x-ui.checkbox`)

Checkbox input with optional label and description.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `name` | string \| null | `null` | Checkbox name attribute |
| `id` | string \| null | `null` | Checkbox ID (defaults to name) |
| `checked` | boolean | `false` | Initial checked state |
| `disabled` | boolean | `false` | Disable the checkbox |
| `label` | string \| null | `null` | Label text |
| `description` | string \| null | `null` | Description text below label |
| `error` | boolean | `false` | Show error state styling |

#### Usage Examples

```blade
<!-- Basic checkbox -->
<x-ui.checkbox name="terms" label="I agree to the terms" />

<!-- With description -->
<x-ui.checkbox 
    name="newsletter" 
    label="Subscribe to newsletter"
    description="Receive weekly gift ideas"
/>

<!-- Checked -->
<x-ui.checkbox 
    name="remember" 
    label="Remember me"
    :checked="true"
/>

<!-- Error state -->
<x-ui.checkbox 
    name="required" 
    label="Required field"
    error
/>

<!-- Disabled -->
<x-ui.checkbox 
    name="disabled" 
    label="Cannot change"
    disabled
/>
```

#### Accessibility Notes

- Label is automatically linked via `for` attribute
- Has proper focus states
- Cursor pointer for better UX on the label

---

### Radio (`x-ui.radio`)

Radio button input with optional label and description.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `name` | string \| null | `null` | Radio name attribute (same for group) |
| `id` | string \| null | `null` | Radio ID (auto-generated if not provided) |
| `value` | string \| null | `null` | Radio value when selected |
| `checked` | boolean | `false` | Initial checked state |
| `disabled` | boolean | `false` | Disable the radio |
| `label` | string \| null | `null` | Label text |
| `description` | string \| null | `null` | Description text below label |
| `error` | boolean | `false` | Show error state styling |

#### Usage Examples

```blade
<!-- Radio group -->
<div class="space-y-2">
    <x-ui.radio 
        name="gift-wrap" 
        value="yes" 
        label="Yes, wrap the gift"
    />
    <x-ui.radio 
        name="gift-wrap" 
        value="no" 
        label="No, don't wrap"
    />
</div>

<!-- With description -->
<x-ui.radio 
    name="delivery" 
    value="standard" 
    label="Standard Delivery"
    description="5-7 business days"
    :checked="true"
/>

<!-- Error state -->
<x-ui.radio 
    name="priority" 
    value="express" 
    label="Express"
    error
/>
```

#### Accessibility Notes

- Labels automatically link to radios via `for` attribute
- Has proper focus states
- Cursor pointer for better UX on the label
- Use same `name` for all radios in a group

---

### Badge (`x-ui.badge`)

Small status indicator with variants and optional dot.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `variant` | `'default' \| 'success' \| 'error' \| 'warning' \| 'info'` | `'default'` | Badge color |
| `size` | `'sm' \| 'md' \| 'lg'` | `'md'` | Badge size |
| `dot` | boolean | `false` | Show status dot |

#### Usage Examples

```blade
<!-- Default badge -->
<x-ui.badge>Default</x-ui.badge>

<!-- Success badge -->
<x-ui.badge variant="success">Completed</x-ui.badge>

<!-- Error badge -->
<x-ui.badge variant="error">Failed</x-ui.badge>

<!-- Warning badge -->
<x-ui.badge variant="warning">Pending</x-ui.badge>

<!-- Info badge -->
<x-ui.badge variant="info">New</x-ui.badge>

<!-- With dot -->
<x-ui.badge variant="success" dot>Active</x-ui.badge>

<!-- Small size -->
<x-ui.badge size="sm">Small</x-ui.badge>

<!-- Large size -->
<x-ui.badge size="lg">Large</x-ui.badge>

<!-- Number -->
<x-ui.badge variant="info">42</x-ui.badge>
```

#### Accessibility Notes

- Use for status indication, not as buttons
- Dot provides visual cue for status
- Ensure contrast ratios are met for all variants

---

### Loading (`x-ui.loading`)

Animated spinner for loading states.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `size` | `'xs' \| 'sm' \| 'md' \| 'lg' \| 'xl'` | `'md'` | Spinner size |
| `color` | `'primary' \| 'neutral' \| 'white'` | `'primary'` | Spinner color |
| `label` | string \| null | `null` | Screen reader text |

#### Usage Examples

```blade
<!-- Default spinner -->
<x-ui.loading />

<!-- Small -->
<x-ui.loading size="sm" />

<!-- Large -->
<x-ui.loading size="lg" />

<!-- Extra large -->
<x-ui.loading size="xl" />

<!-- White (for dark backgrounds) -->
<x-ui.loading color="white" />

<!-- Neutral (for subtle loading) -->
<x-ui.loading color="neutral" />

<!-- With screen reader label -->
<x-ui.loading label="Loading your profile..." />
```

#### Accessibility Notes

- Has `role="status"` for screen readers
- Label is announced via `aria-label` and `sr-only` class
- Use `label` prop when spinner is the only content

---

### Modal (`x-ui.modal`)

Modal dialog with backdrop and close functionality.

#### Props

| Prop | Type | Default | Description |
|------|------|----------|-------------|
| `id` | string \| null | `null` | Modal ID (required for proper ARIA) |
| `open` | boolean | `false` | Show/hide modal |
| `size` | `'sm' \| 'md' \| 'lg' \| 'xl' \| 'full'` | `'md'` | Modal size |
| `closeOnBackdrop` | boolean | `true` | Close when clicking backdrop |
| `closeOnEscape` | boolean | `true` | Close when pressing Escape |

#### Usage Examples

```blade
<x-ui.modal id="example-modal" :open="$showModal" size="lg">
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Modal Title</h2>
        <p class="mb-4">This is the modal content.</p>
        <div class="flex justify-end gap-2">
            <x-ui.button variant="secondary" onclick="document.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'example-modal' } }))">Cancel</x-ui.button>
            <x-ui.button>Confirm</x-ui.button>
        </div>
    </div>
</x-ui.modal>

<!-- Small modal -->
<x-ui.modal id="small-modal" :open="$showSmall" size="sm">
    <!-- Content -->
</x-ui.modal>

<!-- Full width on mobile -->
<x-ui.modal id="full-modal" :open="$showFull" size="full">
    <!-- Content -->
</x-ui.modal>

<!-- No backdrop close -->
<x-ui.modal id="persistent-modal" :open="$showPersistent" :closeOnBackdrop="false">
    <!-- User must click close button -->
</x-ui.modal>
```

#### Accessibility Notes

- Has `role="dialog"` and `aria-modal="true"`
- Close button has proper `aria-label`
- Focus trap (handled by browser when using Alpine.js)
- Keyboard escape to close

---

## Dark Mode

All components support dark mode via the `dark:` prefix. To enable dark mode in your application:

1. Add `class="dark"` to the `<html>` element based on user preference
2. Configure Tailwind's dark mode strategy in `tailwind.config.js`

Example:
```javascript
// tailwind.config.js
module.exports = {
  darkMode: 'class', // or 'media' for system preference
  // ...
}
```

## Color System

Components use the following semantic colors from the style guide:

- **Primary Green**: `primary-600` (#16A34A) for actions, success states
- **Accent Blue**: `accent-600` (#2563EB) for links, info states, focus rings
- **Error Red**: `red-600` (#EF4444) for errors, destructive actions
- **Warning Amber**: `amber-500` (#F59E0B) for warnings
- **Neutral Grays**: `neutral-50` through `neutral-900` for text and backgrounds

## Accessibility Guidelines

All components follow WCAG 2.1 AA standards:

- **Contrast Ratios**: 4.5:1 for normal text, 3:1 for large text
- **Focus States**: 2px ring offset from element
- **Touch Targets**: Minimum 44px × 44px for interactive elements
- **Keyboard Navigation**: All interactive elements are keyboard accessible
- **ARIA Attributes**: Proper roles and labels where needed
- **Screen Readers**: Live regions for dynamic content (alerts, loading)

## Browser Support

All components work in modern browsers:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Best Practices

1. **Use form-group with all form controls** for proper labeling and error handling
2. **Provide meaningful ARIA labels** when content is icon-only
3. **Test with keyboard navigation** to ensure all interactive elements are accessible
4. **Validate color contrast** when customizing colors
5. **Use semantic variants** (success, error, warning, info) for alerts and badges
6. **Consider loading states** for async actions
7. **Handle modal focus** properly when showing/hiding modals

## Contributing

When adding new components:

1. Follow the existing component structure
2. Include dark mode classes for all color-related styles
3. Add proper ARIA attributes
4. Document props and usage examples
5. Test keyboard navigation
6. Ensure contrast ratios meet WCAG AA standards

---

*Last updated: February 2026 - Phase 3 UI/UX Overhaul*
