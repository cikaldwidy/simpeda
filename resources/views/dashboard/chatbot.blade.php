@extends('layouts.dashboard')

@section('title', config('app.name') . ' | Layanan Chatbot')
@section('dashboard_title', 'Layanan Chatbot')
@section('dashboard_subtitle', 'Asisten digital untuk membantu informasi layanan surat.')

@push('styles')
<style>
/* Animated Background Grid */
.animated-grid {
    background-image:
        linear-gradient(to right, rgba(249, 115, 22, .05) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(249, 115, 22, .05) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: gridMove 30s linear infinite;
}


@keyframes orbGlow {

    0%,
    100% {
        transform: scale(1);
        box-shadow: 0 0 20px rgba(249, 115, 22, .3), 0 0 40px rgba(249, 115, 22, .1);
    }

    50% {
        transform: scale(1.05);
        box-shadow: 0 0 30px rgba(249, 115, 22, .5), 0 0 60px rgba(249, 115, 22, .2);
    }
}

/* Pulse Animation for Status */
.pulse-dot {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes gridMove {
    0% {
        background-position: 0 0;
    }

    100% {
        background-position: 40px 40px;
    }
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: .5;
    }
}

/* Typing Indicator Animation - SLOWER */
.typing-dot {
    animation: typingBounce 2s infinite ease-in-out;
}

.typing-dot:nth-child(2) {
    animation-delay: .3s;
}

.typing-dot:nth-child(3) {
    animation-delay: .6s;
}

@keyframes typingBounce {

    0%,
    70%,
    100% {
        transform: translateY(0);
        opacity: .3;
    }

    35% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Slide In Animation for Messages */
.slide-in-left {
    animation: slideInLeft .30s ease-out;
}

.slide-in-right {
    animation: slideInRight .30s ease-out;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Button Hover */
.btn-glow:hover {
    box-shadow: 0 0 20px rgba(249, 115, 22, .4), 0 4px 12px rgba(0, 0, 0, .3);
}

/* Quick Button Hover */
.quick-btn {
    transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quick-btn:hover {
    transform: translateY(-2px);
}

/* Scrollbar Styling */
#chat-messages::-webkit-scrollbar {
    width: 6px;
}

#chat-messages::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, .1);
    border-radius: 10px;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, .4);
    border-radius: 10px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, .6);
}

#quick-actions-panel::-webkit-scrollbar {
    width: 6px;
}

#quick-actions-panel::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, .1);
    border-radius: 10px;
}

#quick-actions-panel::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, .4);
    border-radius: 10px;
}

#quick-actions-panel::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, .6);
}

/* Universal Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, .1);
}

::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, .5);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, .7);
}

/* Fade In Animation */
.fade-in {
    animation: fadeIn .6s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* Shimmer Effect */
.shimmer {
    position: relative;
    overflow: hidden;
}

.shimmer::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(249, 115, 22, .1), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }

    100% {
        left: 100%;
    }
}
</style>
@endpush

@section('dashboard_content')
<div
    class="relative mx-auto flex w-full lg:max-w-5xl h-[calc(100vh-200px)] sm:h-[calc(150vh-9rem)] flex-col overflow-hidden rounded-md bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-900 shadow-2xl">
    <!-- Animated Background -->
    <div class="pointer-events-none absolute inset-0 animated-grid opacity-30"></div>

    <!-- Ambient Light Effects -->
    <div class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 rounded-full bg-orange-500/5 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -right-20 h-64 w-64 rounded-full bg-orange-600/5 blur-3xl">
    </div>

    <div class="relative flex min-h-0 flex-1 flex-col">
        <!-- Chat Header with Bot Info -->
        <div
            class="shimmer overflow-hidden border-b border-neutral-800 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div>
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="w-12 h-12 object-contain flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full">
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider text-orange-100">Chatbot
                        </p>
                        <h5 class="text-xs sm:text-base font-bold text-white uppercase">Layanan Chatbot Desa Wonorejo
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->

        <div class="flex min-h-0 flex-1 flex-col p-2 sm:p-3 ">
            <div id="chat-messages" class="mb-4 flex-1 space-y-4 overflow-y-auto pr-2">
                <!-- Initial Bot Message -->
                <div class="slide-in-left flex items-start gap-3">
                    <div class="mt-1 ">
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="w-7 h-7 object-contain flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full">
                    </div>
                    <div
                        class="max-w-[90%] sm:max-w-[85%] rounded-3xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white">
                        <p class="mb-5 text-sm font-bold text-gray-500">Nara</p>
                        <p class="text-xs sm:text-sm leading-relaxed text-neutral-200">
                            Hai, saya <span class="font-bold text-white">Nara👋</span>. Ada yang bisa saya
                            bantu?
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="rounded-md">
                <form id="chat-form" class="flex flex-row sm:flex-row items-center gap-2 sm:gap-3 ">
                    <input id="chat-input" type="text" placeholder="Ketik perintah..." autocomplete="off"
                        autocorrect="off" autocapitalize="off" spellcheck="false"
                        class="w-full sm:flex-1 rounded-md sm:rounded-lg border border-neutral-700 bg-neutral-800 px-3 sm:px-4 py-2 sm:py-2 text-sm sm:text-sm text-neutral-100 placeholder:text-neutral-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all" />
                    <button id="chat-options-btn" type="button"
                        class="flex items-center justify-center sm:justify-start gap-1 sm:gap-2 rounded-md sm:rounded-lg border border-neutral-700 bg-neutral-800 px-3 sm:px-4 py-2 sm:py-2 text-sm sm:text-sm font-semibold text-neutral-200 shadow-lg transition-all hover:border-orange-500/50 hover:text-orange-300 active:scale-95 whitespace-nowrap ">
                        <i class="fa-solid fa-bars text-xs sm:text-xs"></i>
                        <span class="hidden sm:inline">Opsi</span>
                    </button>
                    <button id="chat-send-btn" type="submit"
                        class="btn-glow flex items-center justify-center sm:justify-start gap-1 sm:gap-2 rounded-md sm:rounded-lg bg-gradient-to-r from-orange-600 to-orange-500 px-4 sm:px-6 py-2 sm:py-2 text-sm sm:text-sm font-semibold text-white shadow-lg transition-all hover:from-orange-500 hover:to-orange-400 active:scale-95 whitespace-nowrap o">
                        <i class="fa-solid fa-paper-plane text-xs sm:text-xs"></i>
                        <span class="hidden sm:inline">Kirim</span>
                    </button>
                </form>
            </div>

            <!-- Quick Actions Panel (Dropdown) -->
            <div id="quick-actions-panel"
                class="hidden mt-2 sm:mt-3 flex max-h-[250px] sm:max-h-[300px] flex-col gap-2 overflow-y-auto rounded-lg sm:rounded-xl border border-neutral-800 bg-black p-2 sm:p-3">
                <button type="button" data-quick-message="cara pengajuan surat"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-file-lines text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Cara
                        Pengajuan
                        Surat</span>
                </button>

                <button type="button" data-quick-message="syarat dokumen surat"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-list-check text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Syarat
                        Dokumen</span>
                </button>

                <button type="button" data-quick-message="status nik {{ $user->nik }}"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-clock-rotate-left text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Cek
                        Status
                        Surat</span>
                </button>

                <button type="button" data-quick-message="buat surat domisili"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-plus text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Buat
                        Surat
                        Domisili</span>
                </button>

                <button type="button" data-quick-message="buat surat tidak mampu"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-file-circle-plus text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Buat
                        Surat
                        SKTM</span>
                </button>

                <button type="button" data-quick-message="buat surat kematian"
                    class="quick-btn group flex items-center gap-2 sm:gap-3 rounded-lg border border-neutral-800 bg-neutral-900 px-2 sm:px-4 py-1.5 sm:py-2 text-left transition-all hover:border-orange-500/50 hover:shadow-lg hover:shadow-orange-500/10">
                    <i class="fa-solid fa-file-circle-plus text-xs sm:text-sm text-orange-400"></i>
                    <span class="text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-orange-300">Buat
                        Surat
                        Kematian</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send-btn');
    const list = document.getElementById('chat-messages');
    const quickButtons = document.querySelectorAll('[data-quick-message]');
    const optionsBtn = document.getElementById('chat-options-btn');
    const panel = document.getElementById('quick-actions-panel');
    if (!form || !input || !sendBtn || !list) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Toggle Quick Actions Panel
    if (optionsBtn && panel) {
        optionsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            panel.classList.toggle('hidden');
        });
    }

    function addUserMessage(text) {
        const wrap = document.createElement('div');
        wrap.className = 'slide-in-right flex justify-end';
        wrap.innerHTML =
            `<div class="max-w-[85%] whitespace-pre-line rounded-2xl rounded-tr-sm border border-orange-600/30 bg-gradient-to-br from-orange-600 to-orange-700 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-orange-500/20">${escapeHtml(text)}</div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function addBotMessage(text) {
        const wrap = document.createElement('div');
        wrap.className = 'slide-in-left flex items-start gap-3';
        wrap.innerHTML =
            `<div class="mt-1 ">
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="w-7 h-7 object-contain flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full">
                    </div>
                 <div class="max-w-[90%] sm:max-w-[85%] rounded-3xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white">
                    <p class="px-4 py-2 text-sm font-bold text-gray-500">Nara</p>
                    <p class="whitespace-pre-line px-4 py-2 text-sm leading-relaxed text-neutral-200">${escapeHtml(text)}</p>
                 </div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function addTypingIndicator() {
        const wrap = document.createElement('div');
        wrap.id = 'chat-typing-indicator';
        wrap.className = 'slide-in-left flex items-start gap-3';
        wrap.innerHTML =
            `<div class="mt-1 ">
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="w-8 h-8 object-contain flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg">
                    </div>
                 <div class="rounded-2xl rounded-tl-md border border-neutral-800 bg-black px-4 py-3 shadow-lg">
                    <div class="mb-2 text-xs font-semibold text-orange-500">Nara sedang mengetik...</div>
                    <div class="flex items-center gap-2">
                        <span class="typing-dot h-2 w-2 rounded-full bg-orange-500"></span>
                        <span class="typing-dot h-2 w-2 rounded-full bg-orange-500"></span>
                        <span class="typing-dot h-2 w-2 rounded-full bg-orange-500"></span>
                    </div>
                 </div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('chat-typing-indicator');
        if (typing) typing.remove();
    }

    function escapeHtml(text) {
        return text
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    async function sendMessage(message) {
        // Close options panel when sending message
        if (panel && !panel.classList.contains('hidden')) {
            panel.classList.add('hidden');
        }

        addUserMessage(message);
        input.value = '';
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50', 'cursor-not-allowed');
        removeTypingIndicator();
        addTypingIndicator();

        try {
            const response = await fetch('{{ route("dashboard.chatbot.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    message
                }),
            });

            const data = await response.json();
            removeTypingIndicator();
            addBotMessage(data.reply || 'Terjadi kendala saat memproses permintaan.');
        } catch (error) {
            removeTypingIndicator();
            addBotMessage('Gagal menghubungi server chatbot. Coba lagi.');
        } finally {
            sendBtn.disabled = false;
            sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            input.focus();
        }
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;
        sendMessage(message);
    });

    quickButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const message = btn.getAttribute('data-quick-message');
            if (!message) return;
            // Close options panel when quick option is selected
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
            }
            sendMessage(message);
        });
    });
})();
</script>
@endpush