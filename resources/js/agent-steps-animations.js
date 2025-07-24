import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', function() {

    const mainTitle = document.getElementById('main-title');
    if (mainTitle) {
        mainTitle.classList.remove('initial-hidden');
        
        const splitTitle = new SplitText(mainTitle, { type: "chars" });
        
        gsap.from(splitTitle.chars, {
            opacity: 0,
            y: 20,
            rotationX: -90,
            stagger: 0.02,
            duration: 0.6,
            ease: "power3.out",
            scrollTrigger: {
                trigger: mainTitle,
                start: "top 85%",
                toggleActions: "play none none none",
            }
        });
    }

    const mainSubtitle = document.getElementById('main-subtitle');
    if (mainSubtitle) {
        mainSubtitle.classList.remove('initial-hidden');

        gsap.from(mainSubtitle, {
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: "power2.out",
            delay: 0.3,
            scrollTrigger: {
                trigger: mainSubtitle,
                start: "top 90%",
                toggleActions: "play none none none",
            }
        });
    }

    const serviceCards = document.querySelectorAll('[data-scroll-card]');
    serviceCards.forEach((card, index) => {
        card.classList.remove('initial-hidden');

        gsap.from(card, {
            opacity: 0,
            y: 50,
            duration: 0.7,
            ease: "power2.out",
            delay: index * 0.1,
            scrollTrigger: {
                trigger: card,
                start: "top 95%",
                toggleActions: "play none none none",
            }
        });
    });

    serviceCards.forEach(card => {
        const iconContainer = card.querySelector('[data-scroll-icon]');
        if (iconContainer) {
            const iconHoverAnimation = gsap.to(iconContainer, {
                scale: 1.1,
                duration: 0.2,
                ease: "power1.out",
                paused: true,
            });

            card.addEventListener('mouseenter', () => iconHoverAnimation.play());
            card.addEventListener('mouseleave', () => iconHoverAnimation.reverse());
        }
    });
});