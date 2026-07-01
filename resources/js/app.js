import './bootstrap';

function showMannaRiseToast(message, type = 'success') {
    const region = document.querySelector('[data-toast-region]');

    if (! region || ! message) {
        return;
    }

    const toast = document.createElement('div');
    toast.className = [
        'pointer-events-auto',
        'translate-x-4',
        'rounded-2xl',
        'border',
        type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-950' : 'border-slate-200 bg-white text-slate-900',
        'px-4',
        'py-3',
        'text-sm',
        'font-bold',
        'shadow-2xl',
        'opacity-0',
        'transition',
        'duration-200',
    ].join(' ');
    toast.setAttribute('role', 'status');

    const row = document.createElement('div');
    row.className = 'flex items-start gap-3';

    const dot = document.createElement('span');
    dot.className = 'mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-600';

    const text = document.createElement('span');
    text.className = 'min-w-0 flex-1 leading-5';
    text.textContent = message;

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'ml-1 shrink-0 rounded-full px-2 text-lg leading-none text-slate-500 hover:bg-white hover:text-slate-900';
    close.setAttribute('aria-label', 'Dismiss alert');
    close.textContent = '×';

    row.append(dot, text, close);
    toast.append(row);
    region.prepend(toast);

    const dismiss = () => {
        toast.classList.add('translate-x-4', 'opacity-0');
        window.setTimeout(() => toast.remove(), 220);
    };

    close.addEventListener('click', dismiss);
    window.setTimeout(() => {
        toast.classList.remove('translate-x-4', 'opacity-0');
    }, 20);
    window.setTimeout(dismiss, 3600);
}

window.showMannaRiseToast = showMannaRiseToast;

window.addEventListener('mannarise-toast', (event) => {
    showMannaRiseToast(event.detail?.message ?? event.detail?.[0] ?? event.detail);
});

function trackMannaRiseGrowth(eventType, payload = {}) {
    const endpoint = document.querySelector('meta[name="growth-analytics-endpoint"]')?.content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (! endpoint || ! csrfToken || ! eventType) {
        return;
    }

    window.fetch(endpoint, {
        method: 'POST',
        keepalive: true,
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            event_type: eventType,
            url: window.location.href,
            path: window.location.pathname,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            screen: {
                width: window.screen?.width,
                height: window.screen?.height,
            },
            ...payload,
        }),
    }).catch(() => {});
}

window.mannaRiseTrackGrowth = trackMannaRiseGrowth;

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-mannarise-toast]').forEach((source) => {
        showMannaRiseToast(source.dataset.mannariseToast);
        source.remove();
    });
});

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
    trackMannaRiseGrowth('pwa_install', {
        standalone: true,
        display_mode: 'standalone',
    });
});

window.addEventListener('click', async (event) => {
    if (event.target.closest('[data-install-dismiss]')) {
        window.localStorage.setItem('mannarise-install-dismissed', 'yes');
        document.querySelector('[data-install-banner]')?.classList.add('hidden');
        return;
    }

    if (event.target.closest('[data-install-now]')) {
        const installed = await window.mannaRiseInstall();

        trackMannaRiseGrowth('install_prompt_click', {
            install_outcome: installed ? 'accepted' : 'dismissed',
            standalone: installed,
        });

        if (! installed) {
            document.querySelector('[data-install-banner]')?.classList.add('hidden');
        }
    }
});
