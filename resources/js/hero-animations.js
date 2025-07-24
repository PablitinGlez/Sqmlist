import { gsap } from 'gsap';
import { TextPlugin } from 'gsap/TextPlugin';

gsap.registerPlugin(TextPlugin);

document.addEventListener('hero-content-loaded', () => {
    const heroTitle = document.getElementById('hero-title');
    
    if (!heroTitle) {
        console.warn("Elemento del hero no encontrado para animar.");
        return;
    }
    
    const originalTitleText = heroTitle.textContent;
    
    heroTitle.innerHTML = '';
    
    gsap.set(heroTitle, {
        opacity: 1,
        text: ""
    });
    
    const tl = gsap.timeline();
    
    tl.to({}, { duration: 0.5 })
    
    .to(heroTitle, {
        duration: 2,
        ease: "none",
        text: {
            value: originalTitleText,
            speed: 1,
            delimiter: ""
        },
        onStart: () => {
            heroTitle.classList.add('typing-cursor');
        },
        onComplete: () => {
            heroTitle.textContent = originalTitleText;
            heroTitle.classList.remove('typing-cursor');
            
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