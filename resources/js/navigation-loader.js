function hideHeroSkeleton() {
    const heroImage = document.getElementById('hero-image');
    if (heroImage) {
        heroImage.classList.remove('opacity-0');
    }

    const heroSkeleton = document.getElementById('hero-skeleton');
    if (heroSkeleton) {
        heroSkeleton.remove();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const navigationLoader = document.getElementById('navigation-loader');
    let isLoadingNavigation = false;
    let loadingTimeout;

    function showNavigationLoader() {
        if (isLoadingNavigation) return;
        isLoadingNavigation = true;
        document.body.classList.add('navigation-loading');
        loadingTimeout = setTimeout(() => {
            if (isLoadingNavigation) {
                navigationLoader.classList.add('active');
            }
        }, 100);
    }

    function hideNavigationLoader() {
        if (!isLoadingNavigation) return;
        clearTimeout(loadingTimeout);
        navigationLoader.classList.remove('active');
        document.body.classList.remove('navigation-loading');
        isLoadingNavigation = false;
    }

    document.addEventListener('livewire:navigate', showNavigationLoader);
    document.addEventListener('livewire:navigated', hideNavigationLoader);

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (
            link &&
            link.href &&
            !link.href.includes('#') &&
            !link.href.includes('javascript:') &&
            !link.hasAttribute('download') &&
            link.target !== '_blank' &&
            link.href.startsWith(window.location.origin) &&
            !link.hasAttribute('wire:navigate')
        ) {
            showNavigationLoader();
        }
    });

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.method && form.method.toLowerCase() === 'post' && form.action) {
            showNavigationLoader();
        }
    });

    window.addEventListener('load', hideNavigationLoader);
    window.addEventListener('beforeunload', function () {
        if (!isLoadingNavigation) {
            showNavigationLoader();
        }
    });
});
