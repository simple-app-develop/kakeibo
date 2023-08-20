    {{-- フラッシュメッセージの表示 --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ __(session('success')) }}</span>
        </div>
    @endif
    @if (session('failure'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ __(session('failure')) }}</span>
        </div>
    @endif
