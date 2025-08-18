# GiftHub Design System & Style Guide

## 1. Design Principles

**Modern & Clean**: Emphasize whitespace, clear typography, and uncluttered layouts
**Accessible**: WCAG 2.1 AA compliance with proper contrast ratios and keyboard navigation
**Consistent**: Unified visual language across all components and pages
**Scalable**: Reusable components and design tokens that grow with the application
**User-Centric**: Intuitive navigation and clear visual hierarchy

## 2. Color System

### Primary Colors
**Primary Green (Brand)**
- `primary-50`: #F0FDF4 (lightest tint)
- `primary-100`: #DCFCE7
- `primary-200`: #BBF7D0
- `primary-300`: #86EFAC
- `primary-400`: #4ADE80
- `primary-500`: #22C55E (secondary green)
- `primary-600`: #16A34A (primary brand color)
- `primary-700`: #15803D
- `primary-800`: #166534
- `primary-900`: #14532D (darkest shade)

**Accent Blue**
- `accent-50`: #EFF6FF
- `accent-100`: #DBEAFE
- `accent-200`: #BFDBFE
- `accent-300`: #93C5FD
- `accent-400`: #60A5FA
- `accent-500`: #3B82F6
- `accent-600`: #2563EB (primary accent)
- `accent-700`: #1D4ED8
- `accent-800`: #1E40AF
- `accent-900`: #1E3A8A

### Neutral Colors
**Grays**
- `neutral-50`: #F8FAFC (lightest background)
- `neutral-100`: #F1F5F9
- `neutral-200`: #E2E8F0
- `neutral-300`: #CBD5E1
- `neutral-400`: #94A3B8
- `neutral-500`: #64748B (primary text gray)
- `neutral-600`: #475569
- `neutral-700`: #334155
- `neutral-800`: #1E293B
- `neutral-900`: #0F172A (darkest text)
- `white`: #FFFFFF
- `black`: #000000

### Semantic Colors
**Success**: `primary-600` (#16A34A)
**Warning**: `#F59E0B` (amber-500)
**Error**: `#EF4444` (red-500)
**Info**: `accent-600` (#2563EB)

## 3. Typography System

### Font Stack
**Primary**: 'Inter', 'Source Sans Pro', system-ui, -apple-system, sans-serif
**Fallback**: Arial, sans-serif

### Font Weights
- `font-normal`: 400 (Regular)
- `font-medium`: 500 (Medium)
- `font-semibold`: 600 (Semi-Bold)
- `font-bold`: 700 (Bold)
- `font-extrabold`: 800 (Extra Bold)

### Typography Scale
**Display Large**: 3.5rem (56px) / Line Height: 1.1 / Weight: 800
**Display**: 2.5rem (40px) / Line Height: 1.2 / Weight: 700
**Headline Large**: 2.25rem (36px) / Line Height: 1.25 / Weight: 700
**Headline**: 2rem (32px) / Line Height: 1.3 / Weight: 600
**Subheadline**: 1.5rem (24px) / Line Height: 1.4 / Weight: 600
**Title**: 1.25rem (20px) / Line Height: 1.4 / Weight: 600
**Body Large**: 1.125rem (18px) / Line Height: 1.6 / Weight: 400
**Body**: 1rem (16px) / Line Height: 1.5 / Weight: 400
**Body Small**: 0.875rem (14px) / Line Height: 1.4 / Weight: 400
**Caption**: 0.75rem (12px) / Line Height: 1.3 / Weight: 400

## 4. Spacing System

### Base Unit: 4px (0.25rem)

**Spacing Scale**
- `space-0`: 0px
- `space-1`: 4px (0.25rem)
- `space-2`: 8px (0.5rem)
- `space-3`: 12px (0.75rem)
- `space-4`: 16px (1rem)
- `space-5`: 20px (1.25rem)
- `space-6`: 24px (1.5rem)
- `space-8`: 32px (2rem)
- `space-10`: 40px (2.5rem)
- `space-12`: 48px (3rem)
- `space-16`: 64px (4rem)
- `space-20`: 80px (5rem)
- `space-24`: 96px (6rem)
- `space-32`: 128px (8rem)

### Layout Spacing
- **Section Padding**: `space-16` (64px) desktop, `space-8` (32px) mobile
- **Component Margin**: `space-6` to `space-12` (24px-48px)
- **Element Spacing**: `space-2` to `space-6` (8px-24px)

## 5. Layout & Grid System

### Container Sizes
- **Max Width**: 1280px (xl)
- **Content Width**: 1024px (lg) for text-heavy content
- **Narrow Width**: 768px (md) for forms and focused content

### Breakpoints
- **Mobile**: 0px - 639px (sm)
- **Tablet**: 640px - 1023px (md)
- **Desktop**: 1024px - 1279px (lg)
- **Large Desktop**: 1280px+ (xl)

### Grid System
- **Columns**: 12-column grid
- **Gutters**: 24px (space-6)
- **Margins**: 16px mobile, 24px tablet+

## 6. Component Specifications

### Buttons
**Primary Button**
- Background: `primary-600`
- Text: `white`
- Hover: `primary-700`
- Focus: 2px `accent-600` ring
- Padding: 12px 24px (space-3 space-6)
- Border Radius: 8px
- Font Weight: 600

**Secondary Button**
- Background: `white`
- Border: 1px `primary-600`
- Text: `primary-600`
- Hover: `primary-600` background, `white` text
- Focus: 2px `accent-600` ring

**Button Sizes**
- Small: 8px 16px (space-2 space-4) / 14px text
- Medium: 12px 24px (space-3 space-6) / 16px text
- Large: 16px 32px (space-4 space-8) / 18px text

### Cards
- Background: `white`
- Border: 1px `neutral-200`
- Border Radius: 12px
- Shadow: `0 1px 3px rgba(0, 0, 0, 0.1)`
- Padding: 24px (space-6)
- Hover Shadow: `0 4px 12px rgba(0, 0, 0, 0.15)`

### Form Elements
**Input Fields**
- Border: 1px `neutral-300`
- Border Radius: 8px
- Padding: 12px 16px (space-3 space-4)
- Focus: 2px `accent-600` ring, `accent-600` border
- Error: `error` border and ring

## 7. Iconography

### Icon System
- **Library**: Heroicons (outline and solid variants)
- **Size**: 16px, 20px, 24px, 32px, 48px
- **Stroke Width**: 1.5px for outline icons
- **Colors**: `neutral-500`, `primary-600`, `white`

### Icon Usage
- **Navigation**: 20px outline icons
- **Buttons**: 16px icons with 8px margin
- **Feature Cards**: 48px icons in `primary-600`
- **Status**: 16px solid icons

## 8. Shadows & Elevation

### Shadow Scale
- **Level 1**: `0 1px 2px rgba(0, 0, 0, 0.05)` (subtle)
- **Level 2**: `0 1px 3px rgba(0, 0, 0, 0.1)` (cards)
- **Level 3**: `0 4px 6px rgba(0, 0, 0, 0.1)` (elevated cards)
- **Level 4**: `0 10px 15px rgba(0, 0, 0, 0.1)` (modals)
- **Level 5**: `0 25px 50px rgba(0, 0, 0, 0.25)` (overlays)

## 9. Border Radius

- **Small**: 4px (form elements, small buttons)
- **Medium**: 8px (buttons, inputs)
- **Large**: 12px (cards, containers)
- **Extra Large**: 16px (hero sections, large components)
- **Full**: 9999px (pills, avatars)

## 10. Accessibility Standards

### Contrast Ratios
- **Normal Text**: 4.5:1 minimum (WCAG AA)
- **Large Text**: 3:1 minimum (WCAG AA)
- **Interactive Elements**: 3:1 minimum

### Focus States
- **Ring**: 2px solid `accent-600`
- **Offset**: 2px from element
- **Visible**: Always visible for keyboard navigation

### Touch Targets
- **Minimum Size**: 44px × 44px
- **Spacing**: 8px minimum between targets
- **Interactive Area**: Extends beyond visual bounds

## 11. Animation & Transitions

### Duration
- **Fast**: 150ms (hover states, simple transitions)
- **Medium**: 300ms (component state changes)
- **Slow**: 500ms (page transitions, complex animations)

### Easing
- **Ease Out**: `cubic-bezier(0, 0, 0.2, 1)` (default)
- **Ease In**: `cubic-bezier(0.4, 0, 1, 1)` (exit animations)
- **Ease In Out**: `cubic-bezier(0.4, 0, 0.2, 1)` (complex animations)

### Properties
- **Hover**: `transition-colors duration-150 ease-out`
- **Transform**: `transition-transform duration-300 ease-out`
- **Opacity**: `transition-opacity duration-200 ease-out`

## 12. Component Library

### Core Components
- **Button**: Primary, secondary, ghost variants with all sizes
- **Card**: Basic, elevated, interactive variants
- **Input**: Text, email, password, textarea with validation states
- **Avatar**: Small (32px), medium (48px), large (64px)
- **Badge**: Status indicators with semantic colors
- **Alert**: Success, warning, error, info variants
- **Modal**: Overlay with backdrop and focus management
- **Dropdown**: Menu with keyboard navigation
- **Tabs**: Horizontal navigation with active states
- **Navbar**: Responsive navigation with mobile menu

### Layout Components
- **Container**: Responsive width constraints
- **Grid**: 12-column responsive grid system
- **Stack**: Vertical spacing utility
- **Cluster**: Horizontal spacing utility
- **Hero**: Large promotional sections
- **Section**: Content sections with consistent spacing

## 13. Usage Guidelines

### Do's
- ✅ Use consistent spacing from the scale
- ✅ Maintain proper contrast ratios
- ✅ Follow the typography hierarchy
- ✅ Use semantic color meanings
- ✅ Implement proper focus states
- ✅ Test with keyboard navigation
- ✅ Optimize for mobile-first design

### Don'ts
- ❌ Create custom colors outside the palette
- ❌ Use arbitrary spacing values
- ❌ Mix font weights inconsistently
- ❌ Ignore accessibility requirements
- ❌ Create components without reusability in mind
- ❌ Use low contrast color combinations

## 14. Implementation Notes

### CSS Custom Properties
All design tokens should be implemented as CSS custom properties for easy theming and maintenance.

### Component Props
All components should accept standard props for customization while maintaining design consistency.

### Responsive Design
All components must work across all breakpoints with appropriate scaling and layout adjustments.

### Performance
Optimize for fast loading with minimal CSS bundle size and efficient component rendering.

---

*This style guide serves as the foundation for all UI development in GiftHub. All new components and pages should adhere to these specifications to maintain consistency and quality.*