// Registra los plugins de GSAP que vas a usar
gsap.registerPlugin(TextPlugin);

document.addEventListener('hero-content-loaded', () => {
    const heroTitle = document.getElementById('hero-title');
    
    if (!heroTitle) {
        console.warn("Elemento del hero no encontrado para animar.");
        return;
    }
    
    // Almacena el texto original del título
    const originalTitleText = heroTitle.textContent;
    
    // Configuración inicial - hace el elemento invisible y vacía el texto
    gsap.set(heroTitle, {
        opacity: 0,
        text: ""
    });
    
    // Timeline para controlar mejor la secuencia
    const tl = gsap.timeline();
    
    // Primero hace visible el elemento
    tl.to(heroTitle, {
        duration: 0.3,
        opacity: 1,
        ease: "power2.out"
    })
    // Luego inicia el efecto de escritura
    .to(heroTitle, {
        duration: 4, // Duración más larga (era 2, ahora 4 segundos)
        delay: 0.2, // Pequeño delay adicional
        ease: "none", // Sin easing para efecto de escritura más natural
        text: {
            value: originalTitleText,
            speed: 0.5, // Velocidad más lenta (era 1, ahora 0.5)
            newClass: "typing" // Opcional: añade clase CSS durante la escritura
        },
        onStart: () => {
            // Opcional: añadir cursor parpadeante
            heroTitle.style.borderRight = "2px solid currentColor";
        },
        onComplete: () => {
            // Asegura que el texto final esté completo
            heroTitle.textContent = originalTitleText;
            // Remueve el cursor parpadeante
            heroTitle.style.borderRight = "none";
            
            // Opcional: pequeña animación final de "bounce"
            gsap.fromTo(heroTitle, 
                { scale: 1 },
                { 
                    scale: 1.02, 
                    duration: 0.2, 
                    ease: "power2.out",
                    yoyo: true,
                    repeat: 1
                }
            );
        }
    });
});