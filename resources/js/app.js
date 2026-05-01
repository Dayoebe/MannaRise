import './bootstrap';

const standalone =
    window.matchMedia('(display-mode: standalone)').matches ||
    window.navigator.standalone === true;

document.documentElement.classList.toggle('is-standalone', standalone);

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {});
    });
}

let installPromptEvent = null;

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installPromptEvent = event;
    window.dispatchEvent(new CustomEvent('mannarise-install-ready'));
});

window.addEventListener('mannarise-install-ready', () => {
    const banner = document.querySelector('[data-install-banner]');

    if (! banner || window.localStorage.getItem('mannarise-install-dismissed') === 'yes') {
        return;
    }

    banner.classList.remove('hidden');
});

window.mannaRiseInstall = async () => {
    if (! installPromptEvent) {
        return false;
    }

    installPromptEvent.prompt();
    const choice = await installPromptEvent.userChoice;
    installPromptEvent = null;

    return choice.outcome === 'accepted';
};

window.addEventListener('appinstalled', () => {
    installPromptEvent = null;
    document.documentElement.classList.add('is-installed');
    document.querySelector('[data-install-banner]')?.classList.add('hidden');
});

window.addEventListener('click', async (event) => {
    if (event.target.closest('[data-install-dismiss]')) {
        window.localStorage.setItem('mannarise-install-dismissed', 'yes');
        document.querySelector('[data-install-banner]')?.classList.add('hidden');
        return;
    }

    if (event.target.closest('[data-install-now]')) {
        const installed = await window.mannaRiseInstall();

        if (! installed) {
            document.querySelector('[data-install-banner]')?.classList.add('hidden');
        }
    }
});
