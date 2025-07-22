// resources/js/app.js

// Importaciones iniciales
import 'ldrs/helix';
import './bootstrap';
import './file-validation';
import './hero-animations';
import './how-it-works-animations';
import './navigation-loader';
import './scroll-animations';
// ¡NUEVA IMPORTACIÓN!
import './advertiser-steps-animations';

document.addEventListener('livewire:init', () => {
    console.log('Livewire inicializado');
});

document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navegado');
});