<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,24rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="timer" class="h-4 w-4" /> Guided prayer</p>
                <h1 class="mt-3 app-section-title">Guided prayer sessions</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose a 3, 5, or 10 minute prayer flow with Scripture, silence, prompts, and a closing declaration.</p>
            </div>
            <div class="app-surface grid grid-cols-3 gap-3 border-rose-200 bg-rose-50 p-4 text-center">
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">3</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-900">Minutes</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">5</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-900">Minutes</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">10</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-900">Minutes</p>
                </div>
            </div>
        </div>
    </section>

    <section data-prayer-session wire:ignore class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)] lg:items-start">
        <div class="space-y-5">
            <div class="app-panel border-rose-200 bg-white">
                <div class="flex flex-wrap gap-2" data-session-tabs>
                    @foreach ($sessions as $key => $session)
                        <button type="button" data-session-key="{{ $key }}" class="rounded-full border px-4 py-2 text-sm font-bold transition">
                            {{ $session['minutes'] }} min
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 rounded-xl border border-rose-100 bg-rose-50 p-5">
                    <p data-session-name class="text-sm font-bold uppercase tracking-normal text-rose-900"></p>
                    <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <p data-session-time class="font-display text-6xl font-black tracking-normal text-slate-950">00:00</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" data-session-start class="btn-primary bg-rose-700 hover:bg-rose-800"><x-ui.icon name="play" class="h-4 w-4" /> Start</button>
                            <button type="button" data-session-pause class="btn-secondary border-rose-200 hover:bg-white"><x-ui.icon name="pause" class="h-4 w-4" /> Pause</button>
                            <button type="button" data-session-reset class="btn-secondary border-rose-200 hover:bg-white"><x-ui.icon name="rotate-ccw" class="h-4 w-4" /> Reset</button>
                        </div>
                    </div>

                    <div class="mt-5 h-3 overflow-hidden rounded-full bg-white">
                        <div data-session-progress class="h-full rounded-full bg-rose-700 transition-all" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <article class="app-panel border-amber-200 bg-amber-50">
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Scripture</p>
                <blockquote data-session-scripture class="mt-4 font-serif text-xl font-semibold leading-8 text-slate-950"></blockquote>
                <p data-session-reference class="mt-3 text-sm font-black text-amber-900"></p>
            </article>

            <article class="app-panel border-emerald-200 bg-emerald-50">
                <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Current prompt</p>
                <h2 data-session-step-title class="mt-4 text-2xl font-black tracking-normal text-slate-950"></h2>
                <p data-session-step-prompt class="mt-3 text-base leading-7 text-slate-700"></p>
            </article>

            <article class="app-panel border-violet-200 bg-violet-50">
                <p class="app-eyebrow border-violet-200 bg-white text-violet-900"><x-ui.icon name="check-circle" class="h-4 w-4" /> Declaration</p>
                <p data-session-declaration class="mt-4 font-serif text-xl font-semibold leading-8 text-slate-950"></p>
            </article>
        </div>

        <aside class="app-panel border-slate-200 bg-white lg:sticky lg:top-36">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="route" class="h-4 w-4 text-rose-800" /> Prayer flow</h2>
            <div data-session-steps class="mt-4 space-y-3"></div>
        </aside>
    </section>

    <script>
        (() => {
            const root = document.querySelector('[data-prayer-session]');

            if (! root || root.dataset.ready === 'true') {
                return;
            }

            root.dataset.ready = 'true';

            const sessions = @json($sessions);
            const tabs = Array.from(root.querySelectorAll('[data-session-key]'));
            const name = root.querySelector('[data-session-name]');
            const time = root.querySelector('[data-session-time]');
            const progress = root.querySelector('[data-session-progress]');
            const scripture = root.querySelector('[data-session-scripture]');
            const reference = root.querySelector('[data-session-reference]');
            const stepTitle = root.querySelector('[data-session-step-title]');
            const stepPrompt = root.querySelector('[data-session-step-prompt]');
            const declaration = root.querySelector('[data-session-declaration]');
            const stepsList = root.querySelector('[data-session-steps]');
            const startButton = root.querySelector('[data-session-start]');
            const pauseButton = root.querySelector('[data-session-pause]');
            const resetButton = root.querySelector('[data-session-reset]');

            let activeKey = '3';
            let remaining = totalSeconds(sessions[activeKey]);
            let timer = null;

            function totalSeconds(session) {
                return session.steps.reduce((total, step) => total + Number(step.seconds), 0);
            }

            function elapsedSeconds() {
                return totalSeconds(sessions[activeKey]) - remaining;
            }

            function activeStepIndex() {
                let cursor = 0;
                const elapsed = elapsedSeconds();

                for (let index = 0; index < sessions[activeKey].steps.length; index += 1) {
                    cursor += Number(sessions[activeKey].steps[index].seconds);

                    if (elapsed < cursor) {
                        return index;
                    }
                }

                return sessions[activeKey].steps.length - 1;
            }

            function format(seconds) {
                const minutes = Math.floor(seconds / 60).toString().padStart(2, '0');
                const rest = Math.floor(seconds % 60).toString().padStart(2, '0');

                return `${minutes}:${rest}`;
            }

            function renderTabs() {
                tabs.forEach((tab) => {
                    const isActive = tab.dataset.sessionKey === activeKey;
                    tab.className = `rounded-full border px-4 py-2 text-sm font-bold transition ${isActive ? 'border-rose-700 bg-rose-700 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'}`;
                });
            }

            function renderSteps() {
                const index = activeStepIndex();

                stepsList.innerHTML = '';

                sessions[activeKey].steps.forEach((step, stepIndex) => {
                    const item = document.createElement('div');
                    const isActive = stepIndex === index;
                    item.className = `rounded-xl border p-3 ${isActive ? 'border-rose-200 bg-rose-50' : 'border-slate-200 bg-white'}`;
                    item.innerHTML = `
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-black tracking-normal text-slate-950">${step.title}</p>
                            <span class="rounded-full bg-mist-100 px-2 py-1 text-xs font-bold text-mist-800">${format(step.seconds)}</span>
                        </div>
                        <p class="mt-1 text-sm leading-6 text-slate-600">${step.prompt}</p>
                    `;
                    stepsList.appendChild(item);
                });
            }

            function render() {
                const session = sessions[activeKey];
                const total = totalSeconds(session);
                const step = session.steps[activeStepIndex()];

                renderTabs();
                name.textContent = session.name;
                time.textContent = format(remaining);
                progress.style.width = `${Math.min(100, ((total - remaining) / total) * 100)}%`;
                scripture.textContent = `"${session.scripture.text}"`;
                reference.textContent = session.scripture.reference;
                stepTitle.textContent = step.title;
                stepPrompt.textContent = step.prompt;
                declaration.textContent = session.declaration;
                renderSteps();
            }

            function stop() {
                if (timer) {
                    window.clearInterval(timer);
                    timer = null;
                }
            }

            function start() {
                stop();

                timer = window.setInterval(() => {
                    remaining = Math.max(0, remaining - 1);
                    render();

                    if (remaining === 0) {
                        stop();
                    }
                }, 1000);
            }

            function reset() {
                stop();
                remaining = totalSeconds(sessions[activeKey]);
                render();
            }

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    activeKey = tab.dataset.sessionKey;
                    reset();
                });
            });

            startButton.addEventListener('click', start);
            pauseButton.addEventListener('click', stop);
            resetButton.addEventListener('click', reset);

            render();
        })();
    </script>
</div>
