import './bootstrap';

// Custom JavaScript will go here
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip functionality replacement
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
    });

    function showTooltip(e) {
        const tooltipText = this.getAttribute('data-tooltip');
        const tooltip = document.createElement('div');
        tooltip.className = 'fixed bg-gray-800 text-white text-sm px-2 py-1 rounded z-50';
        tooltip.textContent = tooltipText;
        tooltip.id = 'custom-tooltip';
        
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.left = `${rect.left + rect.width/2 - tooltip.offsetWidth/2}px`;
        tooltip.style.top = `${rect.bottom + 5}px`;
    }

    function hideTooltip() {
        const tooltip = document.getElementById('custom-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }
});
