import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

window.lucide = { createIcons, icons };

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
