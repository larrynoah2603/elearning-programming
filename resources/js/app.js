import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.plugin(focus);

Alpine.start();

// Custom JavaScript for the application
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Dropdown toggles
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-dropdown-toggle');
            const dropdown = document.getElementById(targetId);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-dropdown-toggle]')) {
            document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Code syntax highlighting initialization (if Prism is loaded)
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }

    // Video player enhancements
    const videoPlayers = document.querySelectorAll('video');
    videoPlayers.forEach(player => {
        player.addEventListener('play', function() {
            // Track video progress (can be extended for analytics)
            console.log('Video started: ' + this.src);
        });
    });

    // Exercise form validation
    const exerciseForms = document.querySelectorAll('.exercise-form');
    exerciseForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const codeInput = this.querySelector('textarea[name="code"]');
            if (codeInput && !codeInput.value.trim()) {
                e.preventDefault();
                alert('Veuillez entrer votre code avant de soumettre.');
                codeInput.focus();
            }
        });
    });

    // Copy code to clipboard
    const copyButtons = document.querySelectorAll('.copy-code-btn');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const codeBlock = this.closest('.code-block').querySelector('code');
            if (codeBlock) {
                navigator.clipboard.writeText(codeBlock.textContent).then(() => {
                    const originalText = this.textContent;
                    this.textContent = 'Copié !';
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 2000);
                });
            }
        });
    });

    // Progress bar animation
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-progress');
        if (width) {
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 300);
        }
    });

    // Tab navigation
    const tabGroups = document.querySelectorAll('[data-tabs]');
    tabGroups.forEach(group => {
        const tabs = group.querySelectorAll('[data-tab]');
        const panels = group.querySelectorAll('[data-tab-panel]');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetPanel = this.getAttribute('data-tab');
                
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.add('hidden'));
                
                this.classList.add('active');
                document.querySelector(`[data-tab-panel="${targetPanel}"]`).classList.remove('hidden');
            });
        });
    });

    // Subscription plan selection
    const planCards = document.querySelectorAll('.plan-card');
    planCards.forEach(card => {
        card.addEventListener('click', function() {
            planCards.forEach(c => c.classList.remove('ring-2', 'ring-primary-500'));
            this.classList.add('ring-2', 'ring-primary-500');
            
            const planInput = document.getElementById('selected-plan');
            if (planInput) {
                planInput.value = this.getAttribute('data-plan');
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (searchInput && searchResults) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                // AJAX search request
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const resultItem = document.createElement('a');
                                resultItem.href = item.url;
                                resultItem.className = 'block px-4 py-2 hover:bg-gray-100';
                                resultItem.innerHTML = `
                                    <div class="font-medium text-gray-900">${item.title}</div>
                                    <div class="text-sm text-gray-500">${item.type}</div>
                                `;
                                searchResults.appendChild(resultItem);
                            });
                            searchResults.classList.remove('hidden');
                        } else {
                            searchResults.innerHTML = '<div class="px-4 py-2 text-gray-500">Aucun résultat trouvé</div>';
                            searchResults.classList.remove('hidden');
                        }
                    });
            }, 300);
        });
    }

    // Dark mode toggle (if implemented)
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        });
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    }
});

// Utility functions
window.utils = {
    // Format date
    formatDate: function(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    },
    
    // Format time
    formatTime: function(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Show notification
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in ${
            type === 'success' ? 'bg-success-500 text-white' :
            type === 'error' ? 'bg-danger-500 text-white' :
            type === 'warning' ? 'bg-warning-500 text-white' :
            'bg-primary-500 text-white'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};
