import './bootstrap';

/*
 * Global JavaScript utilities
 *
 * - Tooltip behaviour (lightweight)
 * - NotificationManager: centralized toast/notification system
 * - Compatibility alias: showToast -> showNotification
 *
 * Notifications are injected into the Blade component:
 *   resources/views/components/ui/notification.blade.php
 *
 * This file intentionally exposes `showNotification` and `showToast`
 * as globals for compatibility with inline page scripts.
 */

/* Tooltip functionality (unchanged) */
document.addEventListener('DOMContentLoaded', function initTooltips() {
  const tooltipElements = document.querySelectorAll('[data-tooltip]');
  tooltipElements.forEach(el => {
    el.addEventListener('mouseenter', showTooltip);
    el.addEventListener('mouseleave', hideTooltip);
  });

  function showTooltip(e) {
    const tooltipText = this.getAttribute('data-tooltip');
    if (!tooltipText) return;
    let tooltip = document.getElementById('custom-tooltip');
    if (!tooltip) {
      tooltip = document.createElement('div');
      tooltip.className = 'fixed bg-gray-800 text-white text-sm px-2 py-1 rounded z-50';
      tooltip.id = 'custom-tooltip';
      document.body.appendChild(tooltip);
    }
    tooltip.textContent = tooltipText;

    const rect = this.getBoundingClientRect();
    tooltip.style.left = `${Math.max(8, rect.left + rect.width/2 - tooltip.offsetWidth/2)}px`;
    tooltip.style.top = `${rect.bottom + 8}px`;
  }

  function hideTooltip() {
    const tooltip = document.getElementById('custom-tooltip');
    if (tooltip) {
      tooltip.remove();
    }
  }
});

/* Notification Manager */
class NotificationManager {
  constructor(containerId = 'notifications') {
    this.container = null;
    // Delay lookup until DOM ready
    document.addEventListener('DOMContentLoaded', () => {
      this.container = document.getElementById(containerId);
      this._processInitialQueue();
    });
    this.defaultDuration = 5000;
  }

  _createElem(type, message, options = {}) {
    const wrapper = document.createElement('div');
    wrapper.className = [
      'notification',
      `notification--${type}`,
      'max-w-sm',
      'w-full',
      'rounded-lg',
      'shadow-lg',
      'pointer-events-auto',
      'ring-1',
      'ring-black/5',
      'backdrop-blur-sm',
      'p-4',
      'mb-2',
      'text-sm',
      'flex',
      'items-start',
      'space-x-3',
      'opacity-0',
      'transform',
      'translate-y-2'
    ].join(' ');
    wrapper.setAttribute('role', 'status');
    wrapper.setAttribute('aria-live', 'polite');

    // Icon container
    const icon = document.createElement('div');
    icon.className = 'flex-shrink-0 mt-0.5';
    icon.innerHTML = this._iconSvg(type);
    wrapper.appendChild(icon);

    // Message
    const content = document.createElement('div');
    content.className = 'flex-1';
    content.innerHTML = `<div class="font-semibold">${this._titleForType(type)}</div><div class="mt-1 text-sm">${message}</div>`;
    wrapper.appendChild(content);

    // Close button
    const btn = document.createElement('button');
    btn.className = 'ml-3 inline-flex text-white/90 hover:text-white focus:outline-none';
    btn.setAttribute('aria-label', 'Dismiss notification');
    btn.innerHTML = `
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>`;
    btn.addEventListener('click', () => this._hide(wrapper));
    wrapper.appendChild(btn);

    // Allow additional classes via options
    if (options.extraClasses) {
      wrapper.className += ' ' + options.extraClasses;
    }

    return wrapper;
  }

  _titleForType(type) {
    switch (type) {
      case 'success': return 'Success';
      case 'error': return 'Error';
      case 'warning': return 'Warning';
      case 'info': default: return 'Info';
    }
  }

  _iconSvg(type) {
    switch (type) {
      case 'success':
        return `<svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
      case 'error':
        return `<svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
      case 'warning':
        return `<svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>`;
      case 'info':
      default:
        return `<svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/></svg>`;
    }
  }

  show({ type = 'info', message = '', duration = null, options = {} } = {}) {
    if (!this.container) {
      // If container not present, fallback to alert()
      console.warn('Notification container not found, falling back to alert.');
      alert(message);
      return;
    }

    const el = this._createElem(type, message, options);
    this.container.appendChild(el);

    // Force reflow then animate in
    requestAnimationFrame(() => {
      el.classList.remove('opacity-0', 'translate-y-2');
      el.classList.add('opacity-100', 'translate-y-0', 'notification-entered');
    });

    // Auto-dismiss timer
    const dismissAfter = duration == null ? (type === 'error' ? 8000 : this.defaultDuration) : duration;
    const timeoutId = setTimeout(() => this._hide(el), dismissAfter);

    // Pause on hover
    el.addEventListener('mouseenter', () => clearTimeout(timeoutId));
    el.addEventListener('mouseleave', () => {
      setTimeout(() => this._hide(el), Math.max(1500, dismissAfter / 3));
    });
  }

  _hide(el) {
    if (!el) return;
    el.classList.add('opacity-0', 'translate-y-2');
    el.classList.remove('opacity-100', 'translate-y-0');
    // After animation, remove
    setTimeout(() => {
      try { el.remove(); } catch (e) { /* ignore */ }
    }, 300);
  }

  _processInitialQueue() {
    if (!window.__initialNotifications || !Array.isArray(window.__initialNotifications)) return;
    window.__initialNotifications.forEach(n => {
      this.show({ type: n.type || 'info', message: n.message || '', duration: n.duration || null });
    });
    // Clear to avoid duplicate processing
    window.__initialNotifications = [];
  }
}

/* Instantiate and expose helpers for compatibility */
window.NotificationManager = new NotificationManager();

window.showNotification = function(message, type = 'info', duration = null, options = {}) {
  if (window.NotificationManager && typeof window.NotificationManager.show === 'function') {
    window.NotificationManager.show({ type, message, duration, options });
  } else {
    // Fallback
    alert(`${type.toUpperCase()}: ${message}`);
  }
};

// Backwards compatibility
window.showToast = window.showNotification;
