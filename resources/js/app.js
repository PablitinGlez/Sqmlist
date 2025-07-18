// Importaciones iniciales
import 'ldrs/helix';
import './bootstrap';
import './file-validation';
import './navigation-loader';
import './scroll-animations';

// NO IMPORTAR ALPINE.JS - Livewire ya lo maneja todo

// Eventos de Livewire
document.addEventListener('livewire:init', () => {
    console.log('Livewire inicializado');
});

document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navegado');
});