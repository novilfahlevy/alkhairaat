<div class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]">
    <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">
        Sistem Database Alkhairaat
    </h3>
    <p class="mb-4 text-gray-500 text-theme-sm dark:text-gray-400">
        Sistem pengelolaan data santri dan alumni Perguruan Islam Alkhairaat secara terpusat.
    </p>
    <div class="text-xs text-gray-400 dark:text-gray-500">
        © {{ date('Y') }} Alkhairaat<br>
        <span class="text-brand-500">{{ auth()->user()->name ?? 'Guest' }}</span>
    </div>
</div>
