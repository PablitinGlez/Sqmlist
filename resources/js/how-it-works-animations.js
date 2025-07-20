// Registra los plugins de GSAP que vas a usar
gsap.registerPlugin(ScrollTrigger, DrawSVGPlugin);

document.addEventListener('DOMContentLoaded', () => {
    const howItWorksSection = document.querySelector('[data-how-it-works-section]');
    const steps = document.querySelectorAll('[data-step]');
    const progressCircles = document.querySelectorAll('[data-step-circle]');
    const progressLines = document.querySelectorAll('[data-line]'); // Las líneas entre los círculos
    const progressLineFill = document.querySelector('[data-progress-line-fill]'); // La línea vertical principal de progreso

    if (!howItWorksSection || steps.length === 0 || progressCircles.length === 0 || progressLines.length === 0 || !progressLineFill) {
        console.warn("Elementos de la sección 'How It Works' no encontrados para animar. Saltando animaciones.");
        return;
    }

    // Obtener la instancia de Alpine.js para actualizar activeStep
    let alpineData = null;
    if (howItWorksSection.__alpine) {
        alpineData = howItWorksSection.__alpine.$data;
    } else {
        // Fallback si Alpine no está inicializado aún, aunque DOMContentLoaded debería ser suficiente
        document.addEventListener('alpine:init', () => {
            if (howItWorksSection.__alpine) {
                alpineData = howItWorksSection.__alpine.$data;
            }
        });
    }

    // Reiniciar el estado inicial de las líneas y círculos
    gsap.set(progressCircles, { drawSVG: "0%" });
    gsap.set(progressLines, { height: 0 });
    gsap.set(progressLineFill, { height: 0 });

    const timeline = gsap.timeline({
        repeat: -1, // Repetir indefinidamente
        repeatDelay: 2, // Retraso de 2 segundos antes de que se repita toda la secuencia
        onStart: () => {
            // Asegurarse de que el primer paso esté activo al inicio de cada ciclo
            if (alpineData) alpineData.activeStep = 1;
            // Reiniciar el estado visual de todos los elementos al inicio de cada ciclo de repetición
            gsap.set(progressCircles, { drawSVG: "0%" });
            gsap.set(progressLines, { height: 0 });
            gsap.set(progressLineFill, { height: 0 });
            // Resetear colores de texto y círculos
            steps.forEach((step, index) => {
                const stepNumberSpan = step.querySelector('span');
                const stepCircleDiv = step.querySelector('div');
                const stepText = step.querySelector('p');
                if (stepNumberSpan) gsap.to(stepNumberSpan, { color: '#4b5563', duration: 0.3 }); // gray-600
                if (stepCircleDiv) gsap.to(stepCircleDiv, { backgroundColor: '#f3f4f6', borderColor: '#d1d5db', duration: 0.3 }); // bg-gray-50, border-gray-300
                if (stepText) gsap.to(stepText, { color: '#4b5563', duration: 0.3 }); // gray-600
            });
        }
    });

    const stepDuration = 3; // Duración de cada paso en segundos

    steps.forEach((step, index) => {
        const stepNumber = index + 1;
        const stepCircle = step.querySelector(`[data-step-circle="${stepNumber}"]`);
        const stepNumberSpan = step.querySelector('span'); // El span con el número
        const stepCircleDiv = step.querySelector('div.relative.flex-shrink-0'); // El div que contiene el SVG y el número
        const stepText = step.querySelector('p'); // El texto del paso

        // Animación para el círculo de progreso
        timeline.to(stepCircle, {
            drawSVG: "100%",
            duration: stepDuration * 0.8, // Completa el círculo antes de pasar al siguiente
            ease: "power1.inOut",
            onStart: () => {
                // Activar el paso en Alpine.js
                if (alpineData) alpineData.activeStep = stepNumber;
                // Animar el color del círculo y el texto del paso actual
                gsap.to(stepNumberSpan, { color: '#ffffff', duration: 0.3 }); // text-white
                gsap.to(stepCircleDiv, { backgroundColor: '#2563eb', borderColor: '#2563eb', duration: 0.3 }); // bg-blue-600, border-blue-600
                gsap.to(stepText, { color: '#111827', duration: 0.3 }); // text-gray-900
            },
            onReverseComplete: () => {
                // Si la animación se revierte (al final del ciclo), resetear colores
                gsap.to(stepNumberSpan, { color: '#4b5563', duration: 0.3 });
                gsap.to(stepCircleDiv, { backgroundColor: '#f3f4f6', borderColor: '#d1d5db', duration: 0.3 });
                gsap.to(stepText, { color: '#4b5563', duration: 0.3 });
            }
        }, `+=${index === 0 ? 0 : 0.5}`); // Comienza el primer paso inmediatamente, los siguientes con un pequeño retraso

        // Animación para la línea vertical de progreso (si no es el último paso)
        if (index < steps.length - 1) {
            const currentLine = document.querySelector(`[data-line="${stepNumber}"]`);
            const lineOffsetTop = currentLine.offsetTop; // Posición inicial de la línea

            timeline.to(currentLine, {
                height: currentLine.offsetHeight, // Anima la altura completa de la línea
                duration: stepDuration * 0.8,
                ease: "power1.inOut"
            }, "<"); // Inicia al mismo tiempo que la animación del círculo

            // Animar la línea principal de progreso
            timeline.to(progressLineFill, {
                height: progressLineFill.offsetHeight + currentLine.offsetHeight, // Aumenta la altura total
                duration: stepDuration * 0.8,
                ease: "power1.inOut"
            }, "<");
        }

        // Pausa al final de cada paso para mostrar el contenido
        timeline.to({}, { duration: stepDuration * 0.2 }); // Pequeña pausa al final del paso
    });

    // Configurar ScrollTrigger para iniciar la timeline cuando la sección sea visible
    ScrollTrigger.create({
        trigger: howItWorksSection,
        start: "top center", // Cuando la parte superior de la sección llega al centro de la ventana
        animation: timeline,
        toggleActions: "play none none reverse", // Play al entrar, nada al salir, nada al volver a entrar, reverse al salir por arriba
        // markers: true // Descomenta para depurar
    });
});
