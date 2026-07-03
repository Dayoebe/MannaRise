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
const installValueKey = 'mannarise-install-value-ready';
const installValueReasonKey = 'mannarise-install-value-reason';
const installDailyPagesKey = 'mannarise-install-daily-pages';
const installDismissedUntilKey = 'mannarise-install-dismissed-until';
const installDailyPageThreshold = 2;
const installDismissSnoozeMs = 7 * 24 * 60 * 60 * 1000;

function getStoredValue(key) {
    try {
        return window.localStorage.getItem(key);
    } catch (error) {
        return null;
    }
}

function setStoredValue(key, value) {
    try {
        window.localStorage.setItem(key, value);
    } catch (error) {
        // Ignore storage failures so install handling never breaks the page.
    }
}

function removeStoredValue(key) {
    try {
        window.localStorage.removeItem(key);
    } catch (error) {
        // Ignore storage failures so install handling never breaks the page.
    }
}

function getStoredJson(key, fallback) {
    try {
        const value = getStoredValue(key);

        return value ? JSON.parse(value) : fallback;
    } catch (error) {
        return fallback;
    }
}

function setStoredJson(key, value) {
    try {
        setStoredValue(key, JSON.stringify(value));
    } catch (error) {
        // Ignore storage failures so install handling never breaks the page.
    }
}

function isInstallDismissed() {
    const dismissedUntil = Number(getStoredValue(installDismissedUntilKey) || 0);

    if (! dismissedUntil) {
        return false;
    }

    if (Date.now() < dismissedUntil) {
        return true;
    }

    removeStoredValue(installDismissedUntilKey);

    return false;
}

function hasInstallValue() {
    if (getStoredValue(installValueKey) === 'yes') {
        return true;
    }

    return getStoredJson(installDailyPagesKey, []).length >= installDailyPageThreshold;
}

function hideInstallBanner() {
    document.querySelector('[data-install-banner]')?.classList.add('hidden');
}

function maybeShowInstallBanner() {
    const banner = document.querySelector('[data-install-banner]');

    if (! banner || standalone || ! installPromptEvent || ! hasInstallValue() || isInstallDismissed()) {
        hideInstallBanner();
        return false;
    }

    banner.classList.remove('hidden');

    return true;
}

function recordInstallValue(reason, detail = {}) {
    if (! reason) {
        return;
    }

    setStoredValue(installValueKey, 'yes');
    setStoredValue(installValueReasonKey, reason);

    window.dispatchEvent(new CustomEvent('mannarise-install-value-ready', {
        detail: { reason, ...detail },
    }));

    maybeShowInstallBanner();
}

function recordDailyInstallVisit() {
    const dailyCard = document.querySelector('[data-daily-devotion-card]');

    if (! dailyCard) {
        return;
    }

    const dailyKey = dailyCard.dataset.dailyDate || window.location.pathname;
    const pages = getStoredJson(installDailyPagesKey, []);
    const nextPages = pages.includes(dailyKey)
        ? pages
        : [...pages, dailyKey].slice(-10);

    setStoredJson(installDailyPagesKey, nextPages);

    if (nextPages.length >= installDailyPageThreshold) {
        recordInstallValue('daily_pages', {
            count: nextPages.length,
        });
    }
}

function recordInstallValueFromClick(event) {
    const dailyAction = event.target.closest('[data-daily-card-action]');
    const devotionalAction = event.target.closest('[data-devotional-share-action]');
    const inviteAction = event.target.closest('[data-invite-share]');
    const valueActions = ['copy', 'download', 'native', 'whatsapp'];
    const action = dailyAction?.dataset.dailyCardAction ||
        devotionalAction?.dataset.devotionalShareAction ||
        inviteAction?.dataset.inviteShare;

    if (! valueActions.includes(action)) {
        return;
    }

    recordInstallValue('card_action', { action });
}

window.mannaRiseRecordInstallValue = recordInstallValue;

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installPromptEvent = event;
    maybeShowInstallBanner();
});

window.addEventListener('mannarise-install-ready', () => {
    maybeShowInstallBanner();
});

window.addEventListener('mannarise-install-value-ready', () => {
    maybeShowInstallBanner();
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
    hideInstallBanner();
    setStoredValue(installValueKey, 'yes');
    trackMannaRiseGrowth('pwa_install', {
        standalone: true,
        display_mode: 'standalone',
    });
});

window.addEventListener('click', async (event) => {
    recordInstallValueFromClick(event);

    if (event.target.closest('[data-install-dismiss]')) {
        setStoredValue(installDismissedUntilKey, String(Date.now() + installDismissSnoozeMs));
        hideInstallBanner();
        return;
    }

    if (event.target.closest('[data-install-now]')) {
        const installed = await window.mannaRiseInstall();

        trackMannaRiseGrowth('install_prompt_click', {
            install_outcome: installed ? 'accepted' : 'dismissed',
            standalone: installed,
        });

        if (! installed) {
            setStoredValue(installDismissedUntilKey, String(Date.now() + installDismissSnoozeMs));
            hideInstallBanner();
        }
    }
});

window.addEventListener('DOMContentLoaded', () => {
    recordDailyInstallVisit();
    maybeShowInstallBanner();
});
