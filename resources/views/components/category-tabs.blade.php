<div x-data="{ activeTab: '{{ $default ?? 'expense' }}' }">
    <div>
        <button x-on:click="activeTab = 'expense'" :class="{ 'bg-indigo-500 text-white': activeTab === 'expense' }"
            class="px-4 py-2 text-sm">支出</button>
        <button x-on:click="activeTab = 'income'" :class="{ 'bg-indigo-500 text-white': activeTab === 'income' }"
            class="px-4 py-2 text-sm">収入</button>
    </div>

    <div x-show="activeTab === 'expense'">
        {{ $expense }}
    </div>

    <div x-show="activeTab === 'income'">
        {{ $income }}
    </div>
</div>
