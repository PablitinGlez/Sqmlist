import { gsap } from 'gsap';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger, DrawSVGPlugin);

document.addEventListener('DOMContentLoaded', () => {
    const howItWorksSection = document.querySelector('[data-how-it-works-section]');
    const steps = document.querySelectorAll('[data-step]');
    const progressCircles = document.querySelectorAll('[data-step-circle]');
    const progressLines = document.querySelectorAll('[data-line]');
    const progressLineFill = document.querySelector('[data-progress-line-fill]');

    if (!howItWorksSection || steps.length === 0 || progressCircles.length === 0 || progressLines.length === 0 || !progressLineFill) {
        console.warn("Elementos de la sección 'How It Works' no encontrados para animar. Saltando animaciones.");
        return;
    }

    let alpineData = null;
    if (howItWorksSection.__alpine) {
        alpineData = howItWorksSection.__alpine.$data;
    } else {
        document.addEventListener('alpine:init', () => {
            if (howItWorksSection.__alpine) {
                alpineData = howItWorksSection.__alpine.$data;
            }
        });
    }

    gsap.set(progressCircles, { drawSVG: "0%" });
    gsap.set(progressLines, { height: 0 });
    gsap.set(progressLineFill, { height: 0 });

    const timeline = gsap.timeline({
        repeat: -1,
        repeatDelay: 2,
        onStart: () => {
            if (alpineData) alpineData.activeStep = 1;
            gsap.set(progressCircles, { drawSVG: "0%" });
            gsap.set(progressLines, { height: 0 });
            gsap.set(progressLineFill, { height: 0 });
            steps.forEach((step, index) => {
                const stepNumberSpan = step.querySelector('span');
                const stepCircleDiv = step.querySelector('div');
                const stepText = step.querySelector('p');
                if (stepNumberSpan) gsap.to(stepNumberSpan, { color: '#4b5563', duration: 0.3 });
                if (stepCircleDiv) gsap.to(stepCircleDiv, { backgroundColor: '#f3f4f6', borderColor: '#d1d5db', duration: 0.3 });
                if (stepText) gsap.to(stepText, { color: '#4b5563', duration: 0.3 });
            });
        }
    });

    const stepDuration = 3;

    steps.forEach((step, index) => {
        const stepNumber = index + 1;
        const stepCircle = step.querySelector(`[data-step-circle="${stepNumber}"]`);
        const stepNumberSpan = step.querySelector('span');
        const stepCircleDiv = step.querySelector('div.relative.flex-shrink-0');
        const stepText = step.querySelector('p');

        timeline.to(stepCircle, {
            drawSVG: "100%",
            duration: stepDuration * 0.8,
            ease: "power1.inOut",
            onStart: () => {
                if (alpineData) alpineData.activeStep = stepNumber;
                gsap.to(stepNumberSpan, { color: '#ffffff', duration: 0.3 });
                gsap.to(stepCircleDiv, { backgroundColor: '#2563eb', borderColor: '#2563eb', duration: 0.3 });
                gsap.to(stepText, { color: '#111827', duration: 0.3 });
            },
            onReverseComplete: () => {
                gsap.to(stepNumberSpan, { color: '#4b5563', duration: 0.3 });
                gsap.to(stepCircleDiv, { backgroundColor: '#f3f4f6', borderColor: '#d1d5db', duration: 0.3 });
                gsap.to(stepText, { color: '#4b5563', duration: 0.3 });
            }
        }, `+=${index === 0 ? 0 : 0.5}`);

        if (index < steps.length - 1) {
            const currentLine = document.querySelector(`[data-line="${stepNumber}"]`);
            const lineOffsetTop = currentLine.offsetTop;

            timeline.to(currentLine, {
                height: currentLine.offsetHeight,
                duration: stepDuration * 0.8,
                ease: "power1.inOut"
            }, "<");

            timeline.to(progressLineFill, {
                height: progressLineFill.offsetHeight + currentLine.offsetHeight,
                duration: stepDuration * 0.8,
                ease: "power1.inOut"
            }, "<");
        }

        timeline.to({}, { duration: stepDuration * 0.2 });
    });

    ScrollTrigger.create({
        trigger: howItWorksSection,
        start: "top center",
        animation: timeline,
        toggleActions: "play none none reverse",
    });
});