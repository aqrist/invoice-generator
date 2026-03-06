<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Invoice Generator') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased">
        {{-- Header --}}
        <header class="sticky top-0 z-20 bg-white/80 dark:bg-zinc-950/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-zinc-900 dark:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span class="font-semibold text-lg">{{ config('app.name', 'Invoice Generator') }}</span>
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-700 dark:hover:bg-zinc-200 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-medium rounded-lg hover:bg-zinc-700 dark:hover:bg-zinc-200 transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        {{-- Hero Section --}}
        <section class="max-w-5xl mx-auto px-6 pt-20 pb-16 text-center">
            <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight leading-tight mb-4">
                Buat Invoice Profesional <br class="hidden sm:block"> dalam Hitungan Menit
            </h1>
            <p class="text-lg text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto mb-8">
                Kelola pelanggan, buat invoice, dan unduh PDF siap kirim. Gratis, cepat, dan mudah digunakan.
            </p>
            @if (Route::has('register'))
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-medium rounded-lg hover:bg-zinc-700 dark:hover:bg-zinc-200 transition text-sm">
                        Mulai Sekarang
                    </a>
                    <a href="#cara-penggunaan" class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-900 transition text-sm">
                        Lihat Panduan
                    </a>
                </div>
            @endif
        </section>

        {{-- Features --}}
        <section class="max-w-5xl mx-auto px-6 py-16">
            <h2 class="text-2xl font-semibold text-center mb-10">Fitur Utama</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Buat Invoice</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Buat invoice lengkap dengan item, diskon, pajak, dan biaya tambahan. Nomor invoice otomatis.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Kelola Pelanggan</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Simpan data pelanggan untuk mempercepat pembuatan invoice berikutnya.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Unduh PDF</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Unduh invoice dalam format PDF profesional, siap dikirim ke pelanggan.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Metode Pembayaran</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Tambahkan rekening bank atau QRIS untuk ditampilkan di invoice.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Profil Perusahaan</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Atur logo, tanda tangan, dan informasi perusahaan untuk tampil di setiap invoice.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-6">
                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-1">Dashboard & Statistik</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Pantau total invoice, pendapatan, tagihan belum dibayar, dan yang sudah jatuh tempo.</p>
                </div>
            </div>
        </section>

        {{-- Step by Step --}}
        <section id="cara-penggunaan" class="max-w-5xl mx-auto px-6 py-16">
            <h2 class="text-2xl font-semibold text-center mb-3">Cara Menggunakan</h2>
            <p class="text-center text-zinc-500 dark:text-zinc-400 mb-12">Ikuti langkah-langkah berikut untuk mulai membuat invoice pertama Anda.</p>

            <div class="space-y-8 relative">
                {{-- Vertical line --}}
                <div class="absolute left-5 top-2 bottom-2 w-px bg-zinc-200 dark:bg-zinc-800 hidden sm:block"></div>

                {{-- Step 1 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        1
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Daftar Akun</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buat akun baru dengan mengklik tombol <strong>Register</strong>. Isi nama, email, dan password Anda.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        2
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Lengkapi Profil Perusahaan</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buka menu <strong>Company Profile</strong> dan isi informasi perusahaan Anda seperti nama, alamat, nomor telepon, email, NPWP, serta upload logo dan tanda tangan.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        3
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Tambahkan Metode Pembayaran</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buka menu <strong>Payment Methods</strong> dan tambahkan rekening bank atau QRIS yang ingin ditampilkan di invoice.
                        </p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        4
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Tambahkan Pelanggan</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buka menu <strong>Customers</strong> dan tambahkan data pelanggan seperti nama, perusahaan, alamat, dan kontak. Data ini bisa digunakan berulang kali.
                        </p>
                    </div>
                </div>

                {{-- Step 5 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        5
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Buat Invoice</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buka menu <strong>Invoices</strong> dan klik <strong>Create Invoice</strong>. Pilih pelanggan, tambahkan item/jasa, atur diskon dan pajak, lalu simpan. Nomor invoice akan dibuat otomatis.
                        </p>
                    </div>
                </div>

                {{-- Step 6 --}}
                <div class="flex gap-5">
                    <div class="flex-shrink-0 size-10 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center font-semibold text-sm relative z-10">
                        6
                    </div>
                    <div class="pt-1.5">
                        <h3 class="font-semibold text-lg mb-1">Unduh & Kirim PDF</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Buka detail invoice, lalu klik tombol <strong>Download PDF</strong>. File PDF siap dikirim ke pelanggan melalui email atau WhatsApp.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-5xl mx-auto px-6 py-16">
            <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-10 text-center">
                <h2 class="text-2xl font-semibold mb-3">Siap Membuat Invoice?</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mb-6">Daftar sekarang dan buat invoice profesional pertama Anda.</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-medium rounded-lg hover:bg-zinc-700 dark:hover:bg-zinc-200 transition text-sm">
                        Daftar Gratis
                    </a>
                @endif
            </div>
        </section>

        {{-- Tech Stack --}}
        <section class="max-w-5xl mx-auto px-6 py-16">
            <h2 class="text-2xl font-semibold text-center mb-3">Dibangun Dengan</h2>
            <p class="text-center text-zinc-500 dark:text-zinc-400 mb-10">Teknologi modern untuk performa dan pengalaman terbaik.</p>
            <div class="flex flex-wrap items-center justify-center gap-8">
                {{-- Laravel --}}
                <a href="https://laravel.com" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg class="size-8" viewBox="0 0 50 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.8.8 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.02-.019.047-.033.071-.05.03-.024.056-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.031.02.058.045.087.069.024.017.051.031.07.05.028.028.048.06.072.093.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.02-.019.047-.033.07-.05.032-.024.057-.05.089-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533c.033.02.06.045.09.069.023.017.05.031.07.05.027.028.047.06.071.093.018.024.04.045.055.071.022.039.036.082.051.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.27v31.44L19.22 48.856v-9.212l-9.204-5.209-.006-.004-.004-.002c-.031-.018-.057-.044-.086-.066-.024-.02-.05-.035-.069-.054l-.003-.004c-.026-.025-.044-.056-.066-.085-.02-.025-.044-.046-.06-.074l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-21.48L4.965 9.207 1.602 7.271zm8.81-5.662L2.4 6.334l8.01 4.609 8.01-4.61-8.008-4.724zm4.164 28.764 4.645-2.674V7.271l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.01 4.609 8.01 4.609 8.005-4.61-8.005-4.608zm-.801 10.605-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z" fill="#FF2D20"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Laravel 12</span>
                </a>

                {{-- Livewire --}}
                <a href="https://livewire.laravel.com" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg class="size-8" viewBox="0 0 67 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.76 54.394c-4.793.036-8.42-2.2-9.937-5.668-2.3-5.264.126-11.07.54-12.4.106-.337.163-.55.163-.55l.009.007c.026-.082.058-.16.085-.242-2.573-5.2-2.216-10.326-.598-14.046 2.095-4.818 6.564-7.327 10.08-8.153 0 0 .092.17.24.468A38.634 38.634 0 0 1 30.4 7.5C34.645 3.3 39.567.51 42.502.052a.654.654 0 0 1 .133-.023.293.293 0 0 1 .065-.01c.03-.006.063-.01.097-.013a.624.624 0 0 1 .097-.006c.14 0 .327.082.373.35.44 2.552-1.258 6.882-4.12 11.537.42-.028.842-.044 1.268-.044 6.36 0 12.202 3.622 14.55 9.14a16.097 16.097 0 0 1 1.268 6.316c3.42 2.78 5.605 6.44 6.168 10.592.753 5.55-1.6 10.973-6.293 14.493-2.61 1.957-5.732 3.118-8.898 3.545a29.136 29.136 0 0 1-4.057.287c-1.212 0-2.402-.083-3.506-.237-3.27 5.937-8.78 9.827-14.784 10.017h-.2c-4.56 0-8.49-2.767-10.26-7.254-.41-1.037-.717-2.147-.908-3.318-.02-.114-.035-.228-.05-.338a19.17 19.17 0 0 1-.06-.395 21.09 21.09 0 0 1-.168-2.67c0-.224.008-.45.017-.673.01-.215.024-.432.042-.648.008-.076.015-.15.024-.226zm3.348-.085c-.004.163-.008.327-.008.493 0 .62.035 1.27.114 1.928l.018.124c.013.094.028.187.044.278l.014.09c.142.78.354 1.544.63 2.243 1.31 3.32 4.192 5.35 7.533 5.35h.15c5.04-.16 9.785-3.66 12.633-9.04l-.3-.056c-.158-.032-.312-.065-.466-.1l-.05-.013c-.354-.08-.7-.17-1.04-.273l-.023-.008c-.343-.103-.68-.216-1.012-.34l-.014-.006c-1.95-.736-3.73-1.85-5.138-3.37l-.02-.026a.664.664 0 0 1-.048-.056l-.008-.01c-.04-.05-.072-.093-.11-.142l-.02-.028a15.82 15.82 0 0 1-1.29-1.99.66.66 0 0 1 .247-.87.637.637 0 0 1 .34-.1c.223 0 .44.12.555.334a14.31 14.31 0 0 0 1.162 1.784l.008.01c.03.038.063.078.093.115l.013.015c.038.044.07.08.102.112l.01.012c1.255 1.363 2.862 2.372 4.63 3.043l.05.018c.306.11.614.21.93.3l.045.014c.31.086.625.16.944.225l.06.012.268.05.007.002c1.014.18 2.103.277 3.27.277 1.26 0 2.59-.118 3.762-.275 2.846-.382 5.657-1.422 8.007-3.184 4.047-3.034 6.077-7.677 5.43-12.44-.474-3.497-2.333-6.604-5.225-9.063a.632.632 0 0 1-.147-.178 16.03 16.03 0 0 0-.82-1.048 13.08 13.08 0 0 0-.834-.88 14.356 14.356 0 0 0-4.25-2.978c.002.137.007.273.007.41 0 .662-.038 1.313-.11 1.952a.66.66 0 0 1-.654.582.66.66 0 0 1-.657-.735c.065-.582.1-1.185.1-1.8 0-.282-.01-.56-.033-.836a14.75 14.75 0 0 0-.305-2.297 14.42 14.42 0 0 0-.772-2.29 13.252 13.252 0 0 0-6.372-6.64 12.908 12.908 0 0 0-3.95-1.378 13.775 13.775 0 0 0-2.54-.29 14.386 14.386 0 0 0-1.534.004c-.134.008-.264.02-.396.033a28.766 28.766 0 0 1-1.168 1.633c-.39.51-.8 1.02-1.228 1.53a40.117 40.117 0 0 1-1.464 1.605c-.082.088-.167.175-.252.262a38.482 38.482 0 0 1-1.567 1.534l-.083.076a36.26 36.26 0 0 1-2.11 1.788 34.448 34.448 0 0 1-1.248.932c-.023.016-.046.034-.07.05a33.154 33.154 0 0 1-1.79 1.195l-.128.08c-.14.086-.28.17-.422.252l-.228.133-.05.028a32.236 32.236 0 0 1-1.272.694l-.298.15-.11.054a32.37 32.37 0 0 1-2.027.892l-.063.024c-.062.025-.124.05-.187.072-.29.11-.574.213-.856.31 1.66 2.77 4.77 4.617 8.394 4.617h.003a.66.66 0 0 1 0 1.32h-.004c-4.226 0-7.886-2.16-9.81-5.424-.48.168-.94.34-1.37.528l-.01.004c-.103.046-.206.092-.306.14l-.006.003c-.113.054-.225.11-.334.166l-.023.013c-.098.052-.196.104-.29.158l-.033.02c-.09.05-.177.103-.264.157l-.03.02c-.388.24-.75.5-1.08.784l-.01.012-.132.116-.01.007a8.24 8.24 0 0 0-.553.562l-.012.014a8.31 8.31 0 0 0-.477.6c-1.42 2.005-2.03 4.74-1.52 7.42.026.143.056.285.09.425a.659.659 0 0 1-.486.797.648.648 0 0 1-.153.019.66.66 0 0 1-.643-.504 10.35 10.35 0 0 1-.188-.83 10.654 10.654 0 0 1-.15-1.46 10.19 10.19 0 0 1 .62-4.26 9.543 9.543 0 0 1 1.062-2.056l.038-.053c.114-.164.236-.325.362-.483l.047-.058c.13-.16.266-.316.41-.468l.07-.074c.128-.133.26-.264.4-.39l.028-.026c.082-.073.165-.145.25-.216l.067-.055c.084-.068.168-.136.255-.202l.077-.058.01-.008c.356-.263.742-.507 1.156-.733l.047-.026c.08-.044.162-.088.244-.13l.036-.02c.107-.055.215-.11.326-.163l.007-.003c.097-.046.196-.09.296-.136l.013-.005c.438-.197.907-.382 1.413-.556l.003-.002c.072-.025.147-.05.222-.073-.08-.236-.174-.466-.278-.69-1.542-3.308-4.835-5.16-8.216-5.07l-.127.004c-3.058.125-5.97 2.123-7.765 5.47a.663.663 0 0 1-.584.35.658.658 0 0 1-.585-.958c2.03-3.787 5.382-6.067 8.894-6.184.075-.003.148-.005.222-.006l-.002-.017c-.104-.89-.27-1.66-.488-2.22-.018-.036-.033-.072-.047-.108a8.88 8.88 0 0 0-.235-.498l-.01-.018a7.367 7.367 0 0 0-.28-.484 5.888 5.888 0 0 0-.332-.474l-.046-.055a5.134 5.134 0 0 0-.41-.44l-.022-.022a4.29 4.29 0 0 0-.275-.236 3.5 3.5 0 0 0-.265-.195l-.022-.014a3.09 3.09 0 0 0-.278-.164l-.014-.007c-.048-.026-.097-.05-.146-.072l-.013-.006a2.276 2.276 0 0 0-.41-.154 1.81 1.81 0 0 0-.462-.063c-1.555 0-3.128 2.048-3.695 3.78a.662.662 0 0 1-.63.454h-.004a.66.66 0 0 1-.63-.862c.716-2.186 2.7-4.693 4.96-4.693.257 0 .514.033.764.097.062.016.124.036.186.057l.026.01c.057.022.113.045.17.07l.014.008c.116.055.23.12.344.195l.026.016c.1.066.198.14.296.218l.036.03c.09.074.178.153.264.236l.027.026c.16.155.314.327.462.517a6.83 6.83 0 0 1 .414.595l.026.044c.113.192.22.396.32.61l.017.034c.1.22.192.45.276.694l.004.012.004.01c.17.475.31 1.01.415 1.607a15.7 15.7 0 0 1 .163 1.254c1.046-.232 2.038-.298 2.752-.287a.658.658 0 0 1 .648.67.66.66 0 0 1-.67.648c-.877-.015-2.122.1-3.335.56-.027.01-.053.023-.08.034l-.003.002c-.09.035-.178.07-.264.108l-.016.007c-.105.046-.207.095-.308.146l-.007.004c-.106.054-.21.11-.31.168a.677.677 0 0 1-.076.05l-.002.002c-.082.05-.162.1-.24.153l-.018.012c-.073.05-.144.1-.214.15l-.022.018c-.18.13-.35.27-.51.418l-.054.05c-.072.067-.143.138-.212.21l-.04.044c-.067.073-.133.148-.197.225l-.026.032c-.07.085-.137.173-.2.264-.004.005-.007.012-.012.017-.184.27-.348.565-.49.885a9.137 9.137 0 0 0-.238.606l-.007.022c-.06.176-.116.36-.166.55l-.002.01-.03.122v.003c-.062.263-.114.545-.157.845-.048.346-.085.72-.107 1.124-.003.05-.007.104-.01.157 0 .01 0 .02-.002.03-.008.152-.013.308-.016.47l-.002.108c0 .048-.003.096-.003.145 0 .123.003.248.008.373l.002.048a18.508 18.508 0 0 0 .142 1.91c.003.022.008.046.012.07.02.116.04.23.065.343l.006.03z" fill="#FB70A9"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Livewire 4</span>
                </a>

                {{-- Flux UI --}}
                <a href="https://flux-ui.com" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg class="size-8" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="28" height="28" rx="6" fill="#18181B"/>
                            <path d="M7 9.5C7 8.67 7.67 8 8.5 8H19.5C20.33 8 21 8.67 21 9.5V18.5C21 19.33 20.33 20 19.5 20H8.5C7.67 20 7 19.33 7 18.5V9.5Z" stroke="white" stroke-width="1.5"/>
                            <path d="M10 13H18M10 16H15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Flux UI</span>
                </a>

                {{-- Tailwind CSS --}}
                <a href="https://tailwindcss.com" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg class="size-8" viewBox="0 0 54 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M27 0c-7.2 0-11.7 3.6-13.5 10.8 2.7-3.6 5.85-4.95 9.45-4.05 2.054.514 3.522 2.004 5.147 3.653C30.744 13.09 33.808 16.2 40.5 16.2c7.2 0 11.7-3.6 13.5-10.8-2.7 3.6-5.85 4.95-9.45 4.05-2.054-.514-3.522-2.004-5.147-3.653C36.756 3.11 33.692 0 27 0zM13.5 16.2C6.3 16.2 1.8 19.8 0 27c2.7-3.6 5.85-4.95 9.45-4.05 2.054.514 3.522 2.004 5.147 3.653C17.244 29.29 20.308 32.4 27 32.4c7.2 0 11.7-3.6 13.5-10.8-2.7 3.6-5.85 4.95-9.45 4.05-2.054-.514-3.522-2.004-5.147-3.653C23.256 19.31 20.192 16.2 13.5 16.2z" fill="#06B6D4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Tailwind CSS 4</span>
                </a>

                {{-- Pest --}}
                <a href="https://pestphp.com" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg class="size-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="14" fill="#A855F7"/>
                            <text x="16" y="21" text-anchor="middle" font-size="14" font-weight="bold" fill="white" font-family="sans-serif">P</text>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Pest 4</span>
                </a>

                {{-- DomPDF --}}
                <a href="https://github.com/barryvdh/laravel-dompdf" target="_blank" rel="noopener" class="flex flex-col items-center gap-2 group">
                    <div class="size-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">DomPDF</span>
                </a>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="max-w-5xl mx-auto px-6 py-8 border-t border-zinc-200 dark:border-zinc-800">
            <p class="text-center text-sm text-zinc-400 dark:text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Invoice Generator') }}. All rights reserved.
            </p>
        </footer>
    </body>
</html>
