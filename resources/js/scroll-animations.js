// resources/js/scroll-animations.js
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function() {
    // Animación de línea de tiempo para "Pasos para convertirse en agente"
    const timelineSection = document.querySelector('.timeline-section');
    const timelineItems = document.querySelectorAll('.timeline-item');
    const timelineLine = document.querySelector('.timeline-line');
    
    if (timelineSection && timelineItems.length > 0) {
        // Estado inicial: ocultar todos los elementos
        gsap.set(timelineItems, {
            opacity: 0,
            y: 30
        });
        
        // Estado inicial de la línea
        if (timelineLine) {
            gsap.set(timelineLine, {
                height: "0%"
            });
        }
        
        // Crear ScrollTrigger para la sección
        ScrollTrigger.create({
            trigger: timelineSection,
            start: "top center",
            end: "bottom center",
            pin: true,
            pinSpacing: true,
            onUpdate: self => {
                const progress = self.progress;
                const totalItems = timelineItems.length;
                
                // Animar la línea de progreso
                if (timelineLine) {
                    gsap.to(timelineLine, {
                        height: `${progress * 100}%`,
                        duration: 0.2,
                        ease: "none"
                    });
                }
                
                // Mostrar elementos progresivamente
                timelineItems.forEach((item, index) => {
                    const itemProgress = (index + 1) / totalItems;
                    if (progress >= itemProgress) {
                        gsap.to(item, {
                            opacity: 1,
                            y: 0,
                            duration: 0.5,
                            ease: "power2.out"
                        });
                    }
                });
            }
        });
    }
});