// Importa GSAP y sus plugins. Asegúrate de que estos módulos estén instalados vía npm.
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

// Registra los plugins de GSAP para que estén disponibles
gsap.registerPlugin(ScrollTrigger, SplitText);

// Espera a que el DOM esté completamente cargado antes de ejecutar las animaciones
document.addEventListener('DOMContentLoaded', function() {

    // --- Animación para el título principal (h2) ---
    const mainTitle = document.getElementById('main-title');
    if (mainTitle) {
        // Elimina la clase 'initial-hidden' para que GSAP tome el control
        mainTitle.classList.remove('initial-hidden');
        
        // Inicializa SplitText para dividir el título en caracteres
        const splitTitle = new SplitText(mainTitle, { type: "chars" });
        
        // Crea la animación de entrada para cada carácter
        gsap.from(splitTitle.chars, {
            opacity: 0,          // Empieza completamente transparente
            y: 20,               // Empieza 20px más abajo
            rotationX: -90,      // Empieza rotado en el eje X
            stagger: 0.02,       // Pequeño retraso entre cada carácter para un efecto escalonado
            duration: 0.6,       // Duración total de la animación de los caracteres
            ease: "power3.out",  // Curva de easing para un movimiento suave y natural
            scrollTrigger: {
                trigger: mainTitle, // El elemento que activa la animación
                start: "top 85%",   // Inicia la animación cuando el 85% superior del título entra en vista
                toggleActions: "play none none none", // Reproduce la animación una sola vez al entrar
                // markers: true, // Descomentar para ver marcadores de ScrollTrigger (solo para depuración)
            }
        });
    }

    // --- Animación para el subtítulo principal (p) ---
    const mainSubtitle = document.getElementById('main-subtitle');
    if (mainSubtitle) {
        // Elimina la clase 'initial-hidden'
        mainSubtitle.classList.remove('initial-hidden');

        // Crea la animación de entrada para el subtítulo
        gsap.from(mainSubtitle, {
            opacity: 0,          // Empieza completamente transparente
            y: 30,               // Empieza 30px más abajo
            duration: 0.8,       // Duración de la animación
            ease: "power2.out",  // Curva de easing
            delay: 0.3,          // Retraso para que aparezca después del título
            scrollTrigger: {
                trigger: mainSubtitle, // El elemento que activa la animación
                start: "top 90%",    // Inicia la animación cuando el 90% superior del subtítulo entra en vista
                toggleActions: "play none none none",
                // markers: true,
            }
        });
    }

    // --- Animación para cada tarjeta de servicio ---
    const serviceCards = document.querySelectorAll('[data-scroll-card]');
    serviceCards.forEach((card, index) => {
        // Elimina la clase 'initial-hidden'
        card.classList.remove('initial-hidden');

        // Crea la animación de entrada para cada tarjeta
        gsap.from(card, {
            opacity: 0,          // Empieza completamente transparente
            y: 50,               // Empieza 50px más abajo
            duration: 0.7,       // Duración de la animación
            ease: "power2.out",  // Curva de easing
            delay: index * 0.1,  // Retraso escalonado para que las tarjetas aparezcan una tras otra
            scrollTrigger: {
                trigger: card,       // El elemento que activa la animación
                start: "top 95%",    // Inicia la animación cuando el 95% superior de la tarjeta entra en vista
                toggleActions: "play none none none",
                // markers: true,
            }
        });
    });

    // --- Animación de los iconos al hacer hover sobre la tarjeta ---
    serviceCards.forEach(card => {
        const iconContainer = card.querySelector('[data-scroll-icon]');
        if (iconContainer) {
            // Crea una animación de escala que estará pausada inicialmente
            const iconHoverAnimation = gsap.to(iconContainer, {
                scale: 1.1,         // Escala ligeramente el icono (10% más grande)
                duration: 0.2,      // Duración rápida de la animación de hover
                ease: "power1.out", // Curva de easing
                paused: true,       // La animación está pausada por defecto
            });

            // Adjunta los eventos de mouse para controlar la animación
            card.addEventListener('mouseenter', () => iconHoverAnimation.play());
            card.addEventListener('mouseleave', () => iconHoverAnimation.reverse()); // Revierte la animación al salir
        }
    });
});
