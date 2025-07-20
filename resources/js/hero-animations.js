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
    
    // CLAVE: Limpia completamente el contenido inicial
    heroTitle.innerHTML = '';
    
    // Configuración inicial - elemento visible pero sin texto
    gsap.set(heroTitle, {
        opacity: 1,
        text: "" // Asegura que empiece completamente vacío
    });
    
    // Timeline para controlar mejor la secuencia
    const tl = gsap.timeline();
    
    // Pequeño delay antes de empezar la escritura
    tl.to({}, { duration: 0.5 }) // Delay inicial de 0.5 segundos
    
    // Efecto de escritura
    .to(heroTitle, {
        duration: 2, // Duración de 4 segundos
        ease: "none", // Sin easing para efecto de escritura natural
        text: {
            value: originalTitleText,
            speed: 1, // Velocidad constante
            delimiter: "" // Carácter por carácter
        },
        onStart: () => {
            // Añadir cursor parpadeante
            heroTitle.classList.add('typing-cursor');
        },
        onComplete: () => {
            // Asegura que el texto final esté completo
            heroTitle.textContent = originalTitleText;
            // Remueve el cursor parpadeante
            heroTitle.classList.remove('typing-cursor');
            
            // Pequeña animación final de "bounce"
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