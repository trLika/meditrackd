import './bootstrap';
import { initDashboard } from './modules/dashboard.js';
import { initUI } from './modules/ui.js';
import { initForms } from './modules/forms.js';

document.addEventListener("DOMContentLoaded", function() {
    // Initialiser tous les modules
    initDashboard();
    initUI();
    initForms();
});

