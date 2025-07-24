import { gsap } from 'gsap';
import { TextPlugin } from 'gsap/TextPlugin';

gsap.registerPlugin(TextPlugin);

// Función principal de animación
function initHeroAnimation() {
    const heroTitle = document.getElementById('hero-title');
    
    if (!heroTitle) {
        console.warn("Elemento hero-title no encontrado");
        return;
    }

    // Obtener el texto original
    const originalText = heroTitle.getAttribute('data-original-text') || heroTitle.textContent.trim();
    
    // Limpiar completamente el elemento
    heroTitle.textContent = '';
    heroTitle.style.opacity = '0';
    
    // Remover cualquier clase de animación previa
    heroTitle.classList.remove('typing-cursor');
    
    // Crear timeline principal
    const masterTimeline = gsap.timeline({
        onStart: () => {
            // Asegurar que el elemento esté visible
            heroTitle.style.opacity = '1';
        }
    });

    // Fase 1: Fade in del título vacío
    masterTimeline.to(heroTitle, {
        opacity: 1,
        duration: 0.3,
        ease: "power2.out"
    });

    // Fase 2: Efecto de escritura
    masterTimeline.to(heroTitle, {
        duration: 2.5,
        ease: "none",
        text: {
            value: originalText,
            speed: 1,
            delimiter: ""
        },
        onStart: () => {
            // Agregar cursor de escritura
            heroTitle.classList.add('typing-cursor');
        },
        onComplete: () => {
            // Remover cursor y asegurar texto final
            heroTitle.classList.remove('typing-cursor');
            heroTitle.textContent = originalText;
        }
    });

    // Fase 3: Efecto de "bounce" sutil al terminar
    masterTimeline.to(heroTitle, {
        scale: 1.03,
        duration: 0.15,
        ease: "power2.out"
    }).to(heroTitle, {
        scale: 1,
        duration: 0.15,
        ease: "power2.out"
    });

    return masterTimeline;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que el contenido del hero esté cargado
    const checkHeroReady = () => {
        const heroContent = document.getElementById('hero-content');
        const heroTitle = document.getElementById('hero-title');
        
        if (heroContent && heroTitle && 
            getComputedStyle(heroContent).opacity === '1') {
            
            // Pequeño delay para asegurar que todo esté renderizado
            setTimeout(() => {
                initHeroAnimation();
            }, 100);
            
            return true;
        }
        return false;
    };

    // Intentar inicializar inmediatamente
    if (!checkHeroReady()) {
        // Si no está listo, escuchar el evento personalizado
        document.addEventListener('hero-content-loaded', () => {
            setTimeout(() => {
                initHeroAnimation();
            }, 200);
        });
    }
});


document.addEventListener('livewire:navigated', () => {
    setTimeout(() => {
        initHeroAnimation();
    }, 300);
});