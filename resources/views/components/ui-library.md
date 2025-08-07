# GiftHub UI Component Library

This document describes the core UI components for the redesigned GiftHub, with Blade usage examples and design rationale.

---

## 1. Button

**Variants:** Primary, Secondary, Disabled

```blade
<!-- Primary Button -->
<button class="gh-btn gh-btn--primary">Primary Action</button>

<!-- Secondary Button -->
<button class="gh-btn gh-btn--secondary">Secondary Action</button>

<!-- Disabled Button -->
<button class="gh-btn gh-btn--primary" disabled>Disabled</button>
```

**Design Rationale:**  
- Large, rounded, bold for approachability and touch targets.
- Primary: Green background, white text.
- Secondary: White background, green border/text.
- Disabled: Gray background/text.

---

## 2. Card

```blade
<div class="gh-card">
  <h3 class="gh-card__title">Card Title</h3>
  <p class="gh-card__body">Card content goes here.</p>
</div>
```

**Design Rationale:**  
- Soft shadow, 12px border-radius, white background for clarity and focus.

---

## 3. Form Field

```blade
<div class="gh-form-group">
  <label for="input" class="gh-label">Label</label>
  <input id="input" class="gh-input" type="text" placeholder="Type here..." />
  <span class="gh-form-hint">Helper or error text</span>
</div>
```

**Design Rationale:**  
- Large input, clear label, focus ring, error/validation state, 8px border-radius.

---

## 4. Navigation Bar

```blade
<nav class="gh-navbar">
  <a href="/" class="gh-navbar__logo">GiftHub</a>
  <div class="gh-navbar__links">
    <a href="/wishlist">My Wishlist</a>
    <a href="/explore">Explore</a>
    <a href="/profile">Profile</a>
  </div>
  <div class="gh-navbar__actions">
    <button class="gh-btn gh-btn--primary">Create a Gift Exchange</button>
    <img src="/avatar.jpg" class="gh-avatar" alt="Profile" />
  </div>
</nav>
```

**Design Rationale:**  
- Horizontal layout, logo left, links center, actions right.
- Responsive: collapses to hamburger on mobile.

---

## 5. Avatar

```blade
<img src="/avatar.jpg" class="gh-avatar" alt="User Avatar" />
```

- 32px or 48px, circular, fallback image if missing.

---

## 6. Tab Bar

```blade
<div class="gh-tabs">
  <button class="gh-tab gh-tab--active">All</button>
  <button class="gh-tab">Public</button>
  <button class="gh-tab">Private</button>
</div>
```

- Underline for active tab, large touch targets.

---

## 7. Alert

```blade
<div class="gh-alert gh-alert--success">Success message</div>
<div class="gh-alert gh-alert--error">Error message</div>
```

- Green for success, red for error, rounded, dismissible.

---

_See the style guide for color, spacing, and typography details. These components will be implemented as Blade partials or components for reuse._