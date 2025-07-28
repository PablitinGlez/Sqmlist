import { gsap } from 'gsap';
import { TextPlugin } from 'gsap/TextPlugin';

gsap.registerPlugin(TextPlugin);

function initHeroAnimation() {
    const heroTitle = document.getElementById('hero-title');

    if (!heroTitle) {
        return;
    }

    const originalText = heroTitle.getAttribute('data-original-text') || heroTitle.textContent.trim();

    heroTitle.textContent = '';
    heroTitle.style.opacity = '0';

    heroTitle.classList.remove('typing-cursor');

    const masterTimeline = gsap.timeline({
        onStart: () => {
            heroTitle.style.opacity = '1';
        }
    });

    masterTimeline.to(heroTitle, {
        opacity: 1,
        duration: 0.3,
        ease: "power2.out"
    });

    masterTimeline.to(heroTitle, {
        duration: 2.5,
        ease: "none",
        text: {
            value: originalText,
            speed: 1,
            delimiter: ""
        },
        onStart: () => {
            heroTitle.classList.add('typing-cursor');
        },
        onComplete: () => {
            heroTitle.classList.remove('typing-cursor');
            heroTitle.textContent = originalText;
        }
    });

    masterTimeline.to(heroTitle, {
        scale: 1.03,
        duration: 0.15,
        ease: "power2.out"
    }).to(heroTitle, {
        scale: 1,
        duration: 0.15,
        ease: "power2.out"
    });

    return masterTimeline;
}

document.addEventListener('DOMContentLoaded', function() {
    const checkHeroReady = () => {
        const heroContent = document.getElementById('hero-content');
        const heroTitle = document.getElementById('hero-title');

        if (heroContent && heroTitle &&
            getComputedStyle(heroContent).opacity === '1') {

            setTimeout(() => {
                initHeroAnimation();
            }, 100);

            return true;
        }
        return false;
    };

    if (!checkHeroReady()) {
        document.addEventListener('hero-content-loaded', () => {
            setTimeout(() => {
                initHeroAnimation();
            }, 200);
        });
    }
});


document.addEventListener('livewire:navigated', () => {
    setTimeout(() => {
        initHeroAnimation();
    }, 300);
});