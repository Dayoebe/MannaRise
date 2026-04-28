<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-indigo-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-cyan-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,28rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="shield" class="h-4 w-4" /> Admin</p>
                <h1 class="mt-3 app-section-title">Project control center</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Create content, manage settings, moderate community submissions, and review the whole MannaRise project from one dashboard.</p>
            </div>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                <a href="{{ route('admin.devotionals') }}" class="btn-primary w-full"><x-ui.icon name="sparkles" class="h-4 w-4" /> Full devotional editor</a>
                <a href="{{ route('admin.settings') }}" class="btn-secondary w-full"><x-ui.icon name="settings" class="h-4 w-4" /> Platform settings</a>
            </div>
        </div>
    </section>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($metricGroups as $metric)
            <div class="metric-card {{ $metric['classes'] }}">
                <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon :name="$metric['icon']" class="h-4 w-4" /> {{ $metric['label'] }}</p>
                <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $metric['value'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)]">
        <div class="app-panel border-amber-200 bg-amber-50">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Content input</p>
                    <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Quick devotional draft</h2>
                </div>
                <a href="{{ route('admin.devotionals') }}" class="btn-secondary border-amber-200 px-3">Open full editor</a>
            </div>

            <form wire:submit="createQuickDevotional" class="mt-5 space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Category</label>
                        <select wire:model="quickDevotionalCategoryId" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                            <option value="">Choose category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('quickDevotionalCategoryId') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Title</label>
                        <input type="text" wire:model="quickDevotionalTitle" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                        @error('quickDevotionalTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700">Bible reference</label>
                    <input type="text" wire:model="quickBibleReference" placeholder="Psalm 23:1" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                    @error('quickBibleReference') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700">Devotional content</label>
                    <textarea wire:model="quickDevotionalContent" rows="6" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
                    @error('quickDevotionalContent') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                        <input type="checkbox" wire:model="quickPublish" class="rounded border-amber-300 text-amber-700 focus:ring-amber-600">
                        Publish now
                    </label>
                    <button type="submit" class="btn-primary bg-amber-600 text-slate-950 hover:bg-amber-500"><x-ui.icon name="sparkles" class="h-4 w-4" /> Save quick draft</button>
                </div>
            </form>
        </div>

        <div class="app-panel border-olive-200 bg-olive-50">
            <p class="app-eyebrow border-olive-200 bg-white text-olive-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> Topic input</p>
            <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Add category</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Create a topic quickly before writing a devotional.</p>

            <form wire:submit="createQuickCategory" class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Category name</label>
                    <input type="text" wire:model="quickCategoryName" class="field-input mt-1 border-olive-300 focus:border-olive-600 focus:ring-olive-100">
                    @error('quickCategoryName') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Description</label>
                    <textarea wire:model="quickCategoryDescription" rows="4" class="field-input mt-1 border-olive-300 focus:border-olive-600 focus:ring-olive-100"></textarea>
                    @error('quickCategoryDescription') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary bg-olive-700 hover:bg-olive-800"><x-ui.icon name="bookmark" class="h-4 w-4" /> Save category</button>
            </form>

            <div class="mt-6 rounded-xl border border-white/70 bg-white p-4">
                <h3 class="font-black tracking-normal text-slate-950">Daily module settings</h3>
                <div class="mt-3 grid gap-2">
                    @foreach ($settingsSnapshot as $setting)
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-3 py-2 text-sm">
                            <span class="font-bold text-slate-700">{{ $setting['label'] }}</span>
                            <span class="font-black text-slate-950">{{ $setting['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="app-eyebrow border-cyan-200 bg-cyan-50 text-cyan-900"><x-ui.icon name="library" class="h-4 w-4" /> Project areas</p>
                <h2 class="mt-3 app-section-title">Everything to manage</h2>
            </div>
            <a href="{{ route('admin.settings') }}" class="btn-secondary w-full sm:w-auto">Settings <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($contentAreas as $area)
                <a href="{{ $area['url'] }}" class="app-panel border-slate-200 transition hover:border-emerald-200 hover:bg-emerald-50">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white text-emerald-800 shadow-sm">
                        <x-ui.icon :name="$area['icon']" class="h-5 w-5" />
                    </span>
                    <h3 class="mt-4 font-black tracking-normal text-slate-950">{{ $area['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $area['description'] }}</p>
                    <p class="mt-3 text-sm font-black text-emerald-900">{{ $area['meta'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
        <div class="app-panel border-slate-200">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="database" class="h-5 w-5 text-slate-800" /> Project content snapshot</h2>
            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                @foreach ($projectSnapshot as $item)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                        <p class="text-xs font-black uppercase tracking-normal text-slate-500">{{ $item['label'] }}</p>
                        <p class="mt-1 text-xl font-black tracking-normal text-slate-950">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-1">
            <section class="app-panel border-emerald-200">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="sparkles" class="h-5 w-5 text-emerald-800" /> Recent devotionals</h2>
                    <a href="{{ route('admin.devotionals') }}" class="text-sm font-bold text-emerald-800 hover:underline">Manage</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($recentDevotionals as $devotional)
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                            <p class="font-black tracking-normal text-slate-950">{{ $devotional->title }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ $devotional->category?->name }} · {{ $devotional->is_published ? 'Published' : 'Draft' }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-slate-600">No devotionals yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="app-panel border-rose-200">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="heart" class="h-5 w-5 text-rose-800" /> Recent prayer requests</h2>
                    <a href="{{ route('admin.prayer-requests') }}" class="text-sm font-bold text-rose-800 hover:underline">Manage</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($recentPrayerRequests as $request)
                        <div class="rounded-xl border border-rose-100 bg-rose-50 p-4">
                            <p class="font-black tracking-normal text-slate-950">{{ $request->title }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ $request->name ?: 'Anonymous' }} · {{ $request->is_answered ? 'Answered' : 'Open' }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-rose-200 bg-rose-50 p-4 text-sm text-slate-600">No prayer requests yet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </section>

    <section class="app-panel border-fuchsia-200">
        <div class="flex items-center justify-between gap-3">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="message-circle" class="h-5 w-5 text-fuchsia-800" /> Recent testimonies</h2>
            <a href="{{ route('admin.testimonies') }}" class="text-sm font-bold text-fuchsia-800 hover:underline">Moderate</a>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($recentTestimonies as $testimony)
                <div class="rounded-xl border border-fuchsia-100 bg-fuchsia-50 p-4">
                    <p class="font-black tracking-normal text-slate-950">{{ $testimony->title }}</p>
                    <p class="mt-1 text-sm font-bold text-slate-500">{{ $testimony->is_approved ? 'Approved' : 'Pending' }} · {{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'Reader') }}</p>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-fuchsia-200 bg-fuchsia-50 p-4 text-sm text-slate-600">No testimonies yet.</p>
            @endforelse
        </div>
    </section>
</div>
