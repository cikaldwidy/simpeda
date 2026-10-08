@extends('layouts.app')
@section('title', config('app.name') . ' | Detail-Artikel')

@section('content')
@include('partials.nav')
<div class="bg-gray-100">
    <header class="sticky top-0 z-0 h-[260px] sm:h-[290px]">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('img/artikel-img.jpg') }}"
                alt="{{ $artikel->judul }}" class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-black/70"></div>
        </div>
        <div
            class="relative z-10 mx-auto flex h-full max-w-6xl flex-col items-center justify-center gap-2 px-4 text-center">
            <div class="mt-16 sm:mt-20">
                <p class="text-xs sm:text-sm text-gray-300 underline font-semibold tracking-[1px] uppercase">
                    {{ $artikel->created_at->format('d M Y') }}
                </p>
                <h1 class="text-base sm:text-xl md:text-2xl font-bold uppercase tracking-[1px] text-white">
                    {{ $artikel->judul }}
                </h1>
            </div>
        </div>
    </header>

    <main class="relative z-10 -mt-[60px] sm:-mt-[50px]">
        <div class="relative h-16 w-full overflow-hidden">
            <div class="absolute top-0 left-1/2 h-full w-[150%] -translate-x-1/2 rounded-t-[50%] bg-gray-100"></div>
        </div>

        <div class="bg-gray-100 pb-16">
            <div class="relative z-10 mx-auto w-full max-w-6xl px-2 md:px-4">
                <div class="flex items-center gap-2 text-gray-700">
                    <a href="{{ url('/') }}" class="flex items-center justify-center transition hover:text-gray-800">
                        <i class="fa-solid fa-house text-2xl mb-2"></i>
                    </a>

                    <span>&rsaquo;</span>

                    <a href="{{ route('artikel') }}"
                        class="flex items-center transition hover:underline text-xs sm:text-sm hover:text-gray-800">
                        ARTIKEL DESA
                    </a>

                    <span>&rsaquo;</span>

                    <span
                        class="flex items-center underline truncate max-w-[160px] sm:max-w-none text-xs sm:text-sm uppercase text-gray-800">
                        {{ \Illuminate\Support\Str::limit($artikel->judul, 50) }}
                    </span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <article class="lg:col-span-9">

                        @if($artikel->gambar)
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}"
                            class="w-full h-60 sm:h-80 lg:h-[450px] object-cover mb-6 mt-6 sm:mt-8">
                        @endif

                        <h1
                            class="text-xl sm:text-3xl lg:text-4xl font-bold text-gray-800 leading-snug tracking-normal break-words uppercase sm:tracking-[1px]">
                            {{ $artikel->judul }}
                        </h1>

                        <p class="mt-2 text-md text-gray-500 underline">
                            {{ $artikel->created_at->format('d M Y') }}
                        </p>

                        <div
                            class="mt-6 text-gray-600 leading-relaxed text-justify text-base md:text-lg tracking-[0.5px] [&_ol]:list-decimal [&_ul]:list-disc [&_ol]:pl-6 [&_ul]:pl-6 [&_li]:mb-1">
                            @php
                            $isiHtml = $artikel->isi ?? '';
                            $isiHtml = preg_replace('/\sdata-[a-z0-9_-]+="[^"]*"/i', '', $isiHtml);
                            @endphp

                            @if($isiHtml !== strip_tags($isiHtml))
                            {!! $isiHtml !!}
                            @else
                            {!! nl2br(e($isiHtml)) !!}
                            @endif
                        </div>

                         <div class="mt-12 border-t pt-8">
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                @if($previousArticle)
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">
                                        &larr; Previous
                                    </p>
                                    <a href="{{ route('artikel.show', $previousArticle->slug) }}" class="group block">
                                        <h3
                                            class="text-md font-bold text-gray-800 group-hover:text-orange-500 transition uppercase hover:underline tracking-[1px]">
                                            {{ $previousArticle->judul }}
                                        </h3>
                                        <img src="{{ $previousArticle->gambar ? asset('storage/' . $previousArticle->gambar) : asset('img/logo_TA.png') }}"
                                            alt="{{ $previousArticle->judul }}"
                                            class="mt-3 w-full h-40 object-cover rounded">
                                    </a>
                                </div>
                                @endif

                                @if($nextArticle)
                                <div class="md:text-right">
                                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">
                                        Next &rarr;
                                    </p>
                                    <a href="{{ route('artikel.show', $nextArticle->slug) }}" class="group block">
                                        <h3
                                            class="text-md font-bold text-gray-800 group-hover:text-orange-500 transition uppercase hover:underline tracking-[1px]">
                                            {{ $nextArticle->judul }}
                                        </h3>
                                        <img src="{{ $nextArticle->gambar ? asset('storage/' . $nextArticle->gambar) : asset('img/logo_TA.png') }}"
                                            alt="{{ $nextArticle->judul }}"
                                            class="mt-3 w-full h-40 object-cover rounded md:ml-auto">
                                    </a>
                                </div>
                                @endif

                             </div>
                         </div>

                        @include('partials.content-comments', [
                            'comments' => $comments,
                            'commentAction' => route('artikel.comments.store', $artikel->slug),
                        ])

                     </article>

                    <aside class="lg:col-span-3 md:py-2 py-0 ">
                        <div class="px-2 py-5">
                            <h3 class="text-md font-semibold text-gray-900 uppercase tracking-[2px]">Pemdes Wonorejo
                            </h3>
                            <p class="mt-5 text-sm text-gray-600 leading-[25px] tracking-[1px]">
                                Jl. Raya Wonorejo RT.01 RW.02, Bendilmuning<br>
                                Desa Wonorejo Kecamatan Sumbergempol<br>
                                Kabupaten Tulungagung<br>
                                Jawa Timur 66291
                            </p>

                            <div class="mt-10">
                                <h4 class="text-md font-semibold text-gray-900 uppercase tracking-[2px]">
                                    Kepala Desa
                                </h4>

                                <div class="mt-5 relative overflow-hidden">
                                    <img src="{{ asset('img/kepala_desaa.png') }}" alt="Kepala Desa"
                                        class="w-full h-auto object-cover">
                                    <div
                                        class="absolute bottom-0 left-0 w-full bg-black/70 text-white text-sm font-semibold text-center p-2">
                                        Anis Wijayanti
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 rounded-md bg-white p-4 shadow-sm">
                                <h4 class="text-md font-semibold text-gray-900 uppercase tracking-[2px]">Pencarian</h4>
                                <form method="GET" action="{{ route('artikel') }}" class="mt-4 flex items-center gap-2">
                                    <input type="text" name="q" placeholder="Cari artikel terkait..."
                                        class="w-full rounded-md px-3 py-1.5 text-sm border border-gray-300 text-xs">
                                    <button type="submit"
                                        class="rounded-md bg-orange-500 px-3 py-2 text-xs font-semibold text-white hover:bg-orange-400 transition">
                                        Cari
                                    </button>
                                </form>
                            </div>

                            @if(isset($recentPosts) && $recentPosts->count())
                            <div class="mt-10">
                                <h4 class="text-md font-semibold text-gray-900 uppercase tracking-[2px]">Recent Posts
                                </h4>
                                <div class="space-y-4 mt-5">
                                    @foreach($recentPosts as $post)
                                    <a href="{{ route('artikel.show', $post->slug) }}"
                                        class="block border-b pb-3 group">
                                        <h5
                                            class="text-sm text-gray-600 group-hover:text-orange-500 transition hover:underline leading-[25px] tracking-[1px]">
                                            {{ $post->judul }}
                                        </h5>
                                        <p class="text-sm text-gray-400 tracking-[1px]">
                                            {{ $post->created_at->format('d/m/Y') }}
                                        </p>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </aside>

                </div>
            </div>
        </div>
    </main>
</div>

@endsection
