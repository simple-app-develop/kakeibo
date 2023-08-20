{{-- フラッシュメッセージの表示 --}}
@if (session('success'))
    <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
        role="alert">
        <span class="block sm:inline">{{ __(session('success')) }}</span>
    </div>
@endif
@if (session('failure'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ __(session('failure')) }}</span>
    </div>
@endif

<script>
    // DOMが完全に読み込まれたら以下のコードを実行
    window.onload = function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            // 3秒後にフェードアウト開始
            setTimeout(() => {
                successMessage.style.transition = 'opacity 1s';
                successMessage.style.opacity = '0';
                // 1秒後にDOMから完全に削除
                setTimeout(() => {
                    if (successMessage.parentNode) {
                        successMessage.parentNode.removeChild(successMessage);
                    }
                }, 1000);
            }, 3000);
        }
    };
</script>
