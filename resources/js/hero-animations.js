// Registra los plugins de GSAP que vas a usar
gsap.registerPlugin(TextPlugin);

document.addEventListener('hero-content-loaded', () => {
    const heroTitle = document.getElementById('hero-title');
    const clicsWord = document.getElementById('clics-word');
    const cursorArrow = document.getElementById('cursor-arrow');

    if (!heroTitle || !clicsWord || !cursorArrow) {
        console.warn("Elementos del hero no encontrados para animar.");
        return;
    }

    // Almacena el texto original del título
    const originalTitleText = heroTitle.textContent;
    // Vacía el título para el efecto de escritura
    heroTitle.textContent = '';

    // Timeline para las animaciones secuenciales
    const masterTimeline = gsap.timeline({
        defaults: { ease: "power2.out" },
        delay: 0.5 // Pequeño retraso antes de que comiencen las animaciones del hero
    });

    // 1. Efecto de escritura del título
    masterTimeline.to(heroTitle, {
        duration: 1.5, // Duración rápida para el efecto de escritura
        text: {
            value: originalTitleText,
            speed: 1 // Velocidad de escritura (1 es por defecto, puedes ajustar)
        },
        onComplete: () => {
            // Asegúrate de que el texto final esté visible si hay algún problema
            heroTitle.textContent = originalTitleText;
        }
    });

    // 2. Animación del cursor/flecha en la palabra "clics"
    masterTimeline.add(() => {
        // Obtener la posición y tamaño de la palabra "clics"
        const clicsRect = clicsWord.getBoundingClientRect();
        const heroContentRect = document.getElementById('hero-content').getBoundingClientRect();

        // Calcular la posición relativa del cursor dentro del contenedor del hero
        const arrowX = clicsRect.left + clicsRect.width / 2 - heroContentRect.left;
        const arrowY = clicsRect.top + clicsRect.height / 2 - heroContentRect.top;

        // Posicionar la flecha inicialmente y hacerla visible
        gsap.set(cursorArrow, {
            x: arrowX,
            y: arrowY,
            opacity: 0,
            display: 'block',
            transformOrigin: "center center" // Asegura que la rotación sea sobre su centro
        });

        // Animación de la flecha
        const arrowTimeline = gsap.timeline({ repeat: -1, repeatDelay: 1 }); // Repetir indefinidamente con un retraso

        // Fase 1: Aparecer y girar alrededor de "clics"
        arrowTimeline.to(cursorArrow, {
            opacity: 1,
            duration: 0.5,
            ease: "power1.out"
        })
        .to(cursorArrow, {
            rotation: 360,
            duration: 2, // Duración de la rotación
            ease: "none",
            repeat: 1 // Repite la rotación una vez
        });

        // Fase 2: Efecto de "clic" en la palabra "clics"
        arrowTimeline.to(clicsWord, {
            scale: 0.9, // Escalar ligeramente hacia abajo
            duration: 0.1,
            ease: "power1.in"
        }, "-=0.5") // Inicia un poco antes de que termine la rotación
        .to(clicsWord, {
            scale: 1, // Vuelve a la escala original
            duration: 0.2,
            ease: "power1.out"
        })
        .to(clicsWord, {
            scale: 0.9, // Segundo clic
            duration: 0.1,
            ease: "power1.in"
        })
        .to(clicsWord, {
            scale: 1, // Vuelve a la escala original
            duration: 0.2,
            ease: "power1.out"
        }, "+=0.5") // Pequeño retraso entre clics
        .to(cursorArrow, { // Opcional: Ocultar la flecha después de los clics o moverla
            opacity: 0,
            duration: 0.5,
            ease: "power1.inOut",
            onComplete: () => {
                gsap.set(cursorArrow, { display: 'none' }); // Ocultar completamente
            }
        }, "+=1"); // Después de un segundo de los clics
        
        // Puedes ajustar el 'repeat' y 'repeatDelay' de arrowTimeline si quieres que la flecha
        // aparezca, haga los clics, desaparezca y luego todo el ciclo se repita, o solo los clics.
        // Para que la flecha se quede y solo los clics se repitan, la lógica sería diferente.
        // Por ahora, la flecha aparece, gira, hace clics y desaparece antes de repetir el ciclo completo.

    }, "+=0.5"); // Inicia la animación de la flecha 0.5 segundos después de que el texto termine de escribirse
});
