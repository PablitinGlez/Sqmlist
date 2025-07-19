// Importa GSAP y ScrollTrigger
// Nota: Ya los cargamos via CDN en app.blade.php, pero esto es buena práctica para módulos JS.
// Si usas npm/yarn para instalar gsap, estas líneas serían:
// import { gsap } from "gsap";
// import { ScrollTrigger } from "gsap/ScrollTrigger";

// Registra el plugin ScrollTrigger con GSAP
// Esto es necesario para usar ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    // Animación para el encabezado principal
    // Se activa cuando la sección con data-scroll-section entra en el viewport
    gsap.from("#main-title", {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: "[data-scroll-section]", // El elemento que dispara la animación
            start: "top 80%", // Cuando la parte superior del trigger está al 80% de la ventana
            toggleActions: "play none none none", // play, pause, resume, reverse
            // markers: true, // Descomentar para depurar la posición del trigger
        }
    });

    gsap.from("#main-subtitle", {
        opacity: 0,
        y: 50,
        duration: 1,
        delay: 0.3, // Pequeño retraso para que aparezca después del título
        ease: "power3.out",
        scrollTrigger: {
            trigger: "[data-scroll-section]",
            start: "top 80%",
            toggleActions: "play none none none",
            // markers: true,
        }
    });

    // Animación para las tarjetas de servicio
    // Se activa cuando el contenedor de servicios entra en el viewport
    gsap.from("[data-scroll-card]", {
        opacity: 0,
        y: 50,
        scale: 0.95,
        duration: 0.8,
        ease: "back.out(1.7)", // Un poco de rebote para que se vea más dinámico
        stagger: 0.2, // Retraso entre la animación de cada tarjeta
        scrollTrigger: {
            trigger: "[data-scroll-services]", // El contenedor de las tarjetas
            start: "top 75%", // Cuando la parte superior del contenedor está al 75% de la ventana
            toggleActions: "play none none none",
            // markers: true,
        }
    });

    // Opcional: Animar los iconos de las tarjetas al pasar el ratón (hover)
    // Esto es CSS puro, pero si quisieras algo más complejo con GSAP:
    // gsap.to("[data-scroll-icon]", {
    //     scale: 1.1,
    //     duration: 0.3,
    //     paused: true,
    //     ease: "power2.out",
    //     overwrite: "auto",
    // });
});

