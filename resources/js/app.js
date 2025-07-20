// Importaciones iniciales
import 'ldrs/helix';
import './bootstrap';
import './file-validation';
import './navigation-loader';
import './scroll-animations';
import './hero-animations';

document.addEventListener('livewire:init', () => {
    console.log('Livewire inicializado');
});

document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navegado');
});