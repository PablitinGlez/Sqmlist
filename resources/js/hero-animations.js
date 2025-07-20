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
    // Vacía el título para el efecto de escritura
    heroTitle.textContent = '';

    // Efecto de escritura del título
    gsap.to(heroTitle, {
        duration: 2,
        delay: 0.5,
        ease: "power2.out",
        text: {
            value: originalTitleText,
            speed: 1
        },
        onComplete: () => {
            heroTitle.textContent = originalTitleText;
        }
    });
});