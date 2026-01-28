import axios from 'axios';
import Alpine from 'alpinejs';

window.axios = axios;

// Configure axios for Laravel
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();
