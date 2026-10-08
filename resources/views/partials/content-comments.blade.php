<section id="komentar" class="mt-12 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-100 text-orange-500">
            <i class="fa-solid fa-comments text-lg"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Komentar</h2>
            <p class="text-sm text-slate-500">Bagikan tanggapan Anda untuk konten ini.</p>
        </div>
    </div>

    @if(session('comment_success'))
    <div class="mt-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('comment_success') }}
    </div>
    @endif

    <form method="POST" action="{{ $commentAction }}" class="mt-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="comment_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                <input id="comment_name" type="text" name="name" value="{{ old('name') }}"
                    class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none focus:ring-0"
                    placeholder="Masukkan nama Anda">
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="comment_email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input id="comment_email" type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none focus:ring-0"
                    placeholder="Masukkan alamat email Anda">
                @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="comment_message" class="mb-2 block text-sm font-semibold text-slate-700">Komentar</label>
            <textarea id="comment_message" name="comment" rows="5"
                class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none focus:ring-0"
                placeholder="Tulis komentar Anda...">{{ old('comment') }}</textarea>
            @error('comment')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
            <i class="fa-solid fa-paper-plane"></i>
            Kirim Komentar
        </button>
    </form>

    <div class="mt-8 border-t border-slate-200 pt-6">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 class="text-lg font-semibold text-slate-800">Komentar Masuk</h3>
            <span class="rounded-xl bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ $comments->count() }} komentar
            </span>
        </div>

        @forelse($comments as $comment)
        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 {{ !$loop->first ? 'mt-3' : '' }}">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-semibold text-slate-800 tracking-[.5px]">{{ $comment->name }}</p>
                    <p class="text-xs text-slate-500">{{ $comment->email }}</p>
                </div>
                <p class="text-xs text-slate-500">{{ $comment->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-700 lowercase">{{ $comment->comment }}
            </p>
        </article>
        @empty
        <div
            class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
            Belum ada komentar untuk konten ini.
        </div>
        @endforelse
    </div>
</section>