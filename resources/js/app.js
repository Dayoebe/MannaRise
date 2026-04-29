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
});
