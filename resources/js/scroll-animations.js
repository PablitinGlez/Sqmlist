gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from("#main-title", {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: "[data-scroll-section]",
            start: "top 80%",
            toggleActions: "play none none none",
        }
    });

    gsap.from("#main-subtitle", {
        opacity: 0,
        y: 50,
        duration: 1,
        delay: 0.3,
        ease: "power3.out",
        scrollTrigger: {
            trigger: "[data-scroll-section]",
            start: "top 80%",
            toggleActions: "play none none none",
        }
    });

    gsap.from("[data-scroll-card]", {
        opacity: 0,
        y: 50,
        scale: 0.95,
        duration: 0.8,
        ease: "back.out(1.7)",
        stagger: 0.2,
        scrollTrigger: {
            trigger: "[data-scroll-services]",
            start: "top 75%",
            toggleActions: "play none none none",
        }
    });
});