# GiftHub Component Specifications

## Component Architecture Overview

This document provides detailed specifications for each UI component in the GiftHub design system, including props, variants, accessibility requirements, and usage examples.

## 1. Button Component Enhancement

### Current Issues
- Limited variant support
- Missing size variations
- Inconsistent focus states

### Enhanced Specifications

**File**: `resources/views/components/ui/button.blade.php`

#### Props
```php
@props([
    'type' => 'button',
    'variant' => 'primary',     // primary, secondary, ghost, danger
    'size' => 'md',             // sm, md, lg, xl
    'disabled' => false,
    'loading' => false,
    'as' => 'button',           // button, a
    'href' => null,
    'icon' => null,             // SVG icon content
    'iconPosition' => 'left',   // left, right
    'fullWidth' => false,
])
```

#### Variants
- **Primary**: `bg-primary-600 text-white hover:bg-primary-700`
- **Secondary**: `bg-white border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white`
- **Ghost**: `bg-transparent text-primary-600 hover:bg-primary-50`
- **Danger**: `bg-error text-white hover:bg-red-600`

#### Sizes
- **sm**: `px-3 py-1.5 text-sm`
- **md**: `px-4 py-2 text-base`
- **lg**: `px-6 py-3 text-lg`
- **xl**: `px-8 py-4 text-xl`

## 2. Card Component Enhancement

### Current Implementation
Basic card with minimal styling options.

### Enhanced Specifications

**File**: `resources/views/components/ui/card.blade.php`

#### Props
```php
@props([
    'variant' => 'default',     // default, elevated, interactive, bordered
    'padding' => 'md',          // sm, md, lg, xl
    'hover' => false,           // Enable hover effects
    'clickable' => false,       // Make entire card clickable
    'href' => null,             // Link destination if clickable
])
```

#### Variants
- **Default**: `bg-white rounded-lg shadow-level-2`
- **Elevated**: `bg-white rounded-lg shadow-level-3`
- **Interactive**: `bg-white rounded-lg shadow-level-2 hover:shadow-level-3 cursor-pointer`
- **Bordered**: `bg-white rounded-lg border border-neutral-200`

## 3. Input Component Enhancement

### Enhanced Specifications

**File**: `resources/views/components/ui/input.blade.php`

#### Props
```php
@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'helpText' => null,
    'size' => 'md',             // sm, md, lg
    'icon' => null,             // Leading icon
    'trailingIcon' => null,     // Trailing icon
])
```

#### States
- **Default**: `border-neutral-300 focus:border-accent-600 focus:ring-accent-600`
- **Error**: `border-error focus:border-error focus:ring-error`
- **Disabled**: `bg-neutral-100 border-neutral-200 text-neutral-400`

## 4. Avatar Component

### New Component Specifications

**File**: `resources/views/components/ui/avatar.blade.php`

#### Props
```php
@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',             // xs, sm, md, lg, xl, 2xl
    'fallback' => null,         // Fallback text/initials
    'status' => null,           // online, offline, away, busy
    'border' => false,          // Add border
])
```

#### Sizes
- **xs**: `w-6 h-6` (24px)
- **sm**: `w-8 h-8` (32px)
- **md**: `w-10 h-10` (40px)
- **lg**: `w-12 h-12` (48px)
- **xl**: `w-16 h-16` (64px)
- **2xl**: `w-20 h-20` (80px)

## 5. Badge Component

### New Component Specifications

**File**: `resources/views/components/ui/badge.blade.php`

#### Props
```php
@props([
    'variant' => 'default',     // default, primary, success, warning, error, info
    'size' => 'md',             // sm, md, lg
    'dot' => false,             // Show as dot indicator
    'removable' => false,       // Show remove button
])
```

#### Variants
- **Default**: `bg-neutral-100 text-neutral-800`
- **Primary**: `bg-primary-100 text-primary-800`
- **Success**: `bg-green-100 text-green-800`
- **Warning**: `bg-yellow-100 text-yellow-800`
- **Error**: `bg-red-100 text-red-800`
- **Info**: `bg-blue-100 text-blue-800`

## 6. Modal Component

### New Component Specifications

**File**: `resources/views/components/ui/modal.blade.php`

#### Props
```php
@props([
    'show' => false,
    'maxWidth' => 'md',         // sm, md, lg, xl, 2xl
    'closeable' => true,
    'title' => null,
    'footer' => null,
])
```

#### Features
- Backdrop blur and click-to-close
- Keyboard navigation (ESC to close)
- Focus management
- Scroll lock when open
- Smooth transitions

## 7. Alert Component Enhancement

### Enhanced Specifications

**File**: `resources/views/components/ui/alert.blade.php`

#### Props
```php
@props([
    'type' => 'info',           // success, warning, error, info
    'dismissible' => false,
    'title' => null,
    'icon' => true,             // Show type icon
    'border' => false,          // Add left border
])
```

#### Types with Icons
- **Success**: Check circle icon, green colors
- **Warning**: Exclamation triangle icon, yellow colors
- **Error**: X circle icon, red colors
- **Info**: Information circle icon, blue colors

## 8. Dropdown Component

### New Component Specifications

**File**: `resources/views/components/ui/dropdown.blade.php`

#### Props
```php
@props([
    'trigger' => null,          // Trigger element content
    'position' => 'bottom-left', // bottom-left, bottom-right, top-left, top-right
    'width' => 'auto',          // auto, sm, md, lg, xl
    'offset' => 4,              // Offset from trigger
])
```

#### Features
- Click and hover triggers
- Keyboard navigation
- Auto-positioning
- Click outside to close
- Smooth animations

## 9. Tabs Component Enhancement

### Enhanced Specifications

**File**: `resources/views/components/ui/tabs.blade.php`

#### Props
```php
@props([
    'variant' => 'default',     // default, pills, underline
    'size' => 'md',             // sm, md, lg
    'fullWidth' => false,
    'vertical' => false,
])
```

#### Variants
- **Default**: Standard tab styling with background
- **Pills**: Rounded pill-style tabs
- **Underline**: Minimal tabs with bottom border

## 10. Loading States

### New Component Specifications

**File**: `resources/views/components/ui/spinner.blade.php`

#### Props
```php
@props([
    'size' => 'md',             // xs, sm, md, lg, xl
    'color' => 'primary',       // primary, white, neutral
    'text' => null,             // Loading text
])
```

**File**: `resources/views/components/ui/skeleton.blade.php`

#### Props
```php
@props([
    'lines' => 3,               // Number of skeleton lines
    'avatar' => false,          // Include avatar skeleton
    'button' => false,          // Include button skeleton
])
```

## 11. Form Components

### Form Group Component

**File**: `resources/views/components/ui/form-group.blade.php`

#### Props
```php
@props([
    'label' => null,
    'required' => false,
    'error' => null,
    'helpText' => null,
    'labelClass' => '',
])
```

### Textarea Component

**File**: `resources/views/components/ui/textarea.blade.php`

#### Props
```php
@props([
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'rows' => 4,
    'resize' => 'vertical',     // none, vertical, horizontal, both
])
```

### Select Component

**File**: `resources/views/components/ui/select.blade.php`

#### Props
```php
@props([
    'label' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'options' => [],            // Array of options
    'multiple' => false,
])
```

## 12. Navigation Components

### Breadcrumb Component

**File**: `resources/views/components/ui/breadcrumb.blade.php`

#### Props
```php
@props([
    'items' => [],              // Array of breadcrumb items
    'separator' => 'chevron',   // chevron, slash, dot
])
```

### Pagination Component

**File**: `resources/views/components/ui/pagination.blade.php`

#### Props
```php
@props([
    'paginator' => null,        // Laravel paginator instance
    'showNumbers' => true,
    'showText' => true,
    'maxLinks' => 7,
])
```

## 13. Feedback Components

### Toast Component

**File**: `resources/views/components/ui/toast.blade.php`

#### Props
```php
@props([
    'type' => 'info',           // success, warning, error, info
    'title' => null,
    'dismissible' => true,
    'duration' => 5000,         // Auto-dismiss duration in ms
    'position' => 'top-right',  // top-right, top-left, bottom-right, bottom-left
])
```

### Progress Component

**File**: `resources/views/components/ui/progress.blade.php`

#### Props
```php
@props([
    'value' => 0,               // Progress value (0-100)
    'max' => 100,
    'size' => 'md',             // sm, md, lg
    'color' => 'primary',       // primary, success, warning, error
    'showLabel' => false,
])
```

## 14. Data Display Components

### Table Component

**File**: `resources/views/components/ui/table.blade.php`

#### Props
```php
@props([
    'striped' => false,
    'bordered' => false,
    'hover' => false,
    'size' => 'md',             // sm, md, lg
    'responsive' => true,
])
```

### Empty State Component

**File**: `resources/views/components/ui/empty-state.blade.php`

#### Props
```php
@props([
    'icon' => null,
    'title' => 'No data found',
    'description' => null,
    'action' => null,           // Call-to-action button
])
```

## 15. Layout Components

### Container Component

**File**: `resources/views/components/ui/container.blade.php`

#### Props
```php
@props([
    'size' => 'default',        // sm, default, lg, xl, full
    'padding' => true,          // Add horizontal padding
])
```

### Grid Component

**File**: `resources/views/components/ui/grid.blade.php`

#### Props
```php
@props([
    'cols' => 1,                // Number of columns
    'gap' => 'md',              // xs, sm, md, lg, xl
    'responsive' => true,       // Enable responsive behavior
])
```

### Stack Component

**File**: `resources/views/components/ui/stack.blade.php`

#### Props
```php
@props([
    'space' => 'md',            // xs, sm, md, lg, xl
    'align' => 'stretch',       // start, center, end, stretch
    'justify' => 'start',       // start, center, end, between, around
])
```

## 16. Accessibility Requirements

### Focus Management
- All interactive components must have visible focus states
- Focus should be trapped in modals and dropdowns
- Focus should return to trigger element when closing overlays

### Keyboard Navigation
- All components must be keyboard accessible
- Arrow keys for navigation in lists and menus
- Enter/Space for activation
- Escape for closing overlays

### Screen Reader Support
- Proper ARIA labels and descriptions
- Role attributes where appropriate
- Live regions for dynamic content updates
- Semantic HTML structure

### Color and Contrast
- All text must meet WCAG 2.1 AA contrast requirements
- Color should not be the only way to convey information
- Focus indicators must have sufficient contrast

## 17. Testing Checklist

### Visual Testing
- [ ] Component renders correctly in all variants
- [ ] Responsive behavior works across breakpoints
- [ ] Hover and focus states are visible
- [ ] Loading states display properly

### Functional Testing
- [ ] All props work as expected
- [ ] Event handlers fire correctly
- [ ] Form validation works
- [ ] Navigation functions properly

### Accessibility Testing
- [ ] Keyboard navigation works
- [ ] Screen reader compatibility
- [ ] Color contrast meets standards
- [ ] Focus management is correct

### Performance Testing
- [ ] Components render efficiently
- [ ] No unnecessary re-renders
- [ ] Smooth animations
- [ ] Fast loading times

## 18. Implementation Notes

### Component Organization
- Each component should be self-contained
- Shared logic should be extracted to utilities
- Props should have sensible defaults
- Documentation should be comprehensive

### Styling Approach
- Use Tailwind utility classes
- Create component-specific utilities when needed
- Maintain consistent spacing and sizing
- Follow the design system tokens

### JavaScript Integration
- Use Alpine.js for interactive behavior
- Keep JavaScript minimal and focused
- Ensure graceful degradation
- Test without JavaScript enabled

This specification serves as the complete blueprint for implementing the GiftHub design system components.