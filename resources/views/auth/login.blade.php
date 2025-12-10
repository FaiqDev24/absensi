<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        blob: "blob 7s infinite",
                    },
                    keyframes: {
                        blob: {
                            "0%": {
                                transform: "translate(0px, 0px) scale(1)"
                            },
                            "33%": {
                                transform: "translate(30px, -50px) scale(1.1)"
                            },
                            "66%": {
                                transform: "translate(-20px, 20px) scale(0.9)"
                            },
                            "100%": {
                                transform: "translate(0px, 0px) scale(1)"
                            },
                        },
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-slate-50 overflow-hidden selection:bg-sky-500 selection:text-white">

    <!-- Animated modern background -->
    <div class="fixed inset-0 min-h-screen z-0">
        <div class="absolute inset-0 bg-slate-50"></div>
        <!-- Green Blob -->
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob">
        </div>
        <!-- Light Blue Blob -->
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-sky-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000">
        </div>
        <!-- Dark Blue Blob -->
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-800 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000">
        </div>

        <!-- Noise overlay -->
        <div class="absolute inset-0 opacity-40 brightness-100 contrast-150 pointer-events-none"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.8%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22 opacity=%220.5%22/%3E%3C/svg%3E');">
        </div>
    </div>

    <div class="min-h-screen flex items-center justify-center relative z-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Glassmorphism Card -->
            <div
                class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-2xl p-8 shadow-2xl relative overflow-hidden ring-1 ring-white/60">

                <!-- Decorative Gradient Bar: Green -> Light Blue -> Dark Blue -->
                <div
                    class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 via-sky-500 to-blue-800">
                </div>

                <div class="text-center mb-8">
                    <!-- Icon Container: Light Blue bg, Dark Blue icon -->
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-100 text-blue-800 mb-4 ring-1 ring-sky-200 shadow-md shadow-sky-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Welcome</h2>
                    <p class="mt-2 text-sm text-slate-500">Silakan login untuk mengakses akun Anda</p>
                </div>

                <!-- Session Messages -->
                @if (session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-5 h-5 flex-shrink-0">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-5 h-5 flex-shrink-0">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-700 block">Email Address</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-sky-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input type="text" id="email" name="email" value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-800 placeholder-slate-400 transition-all duration-200 ease-in-out sm:text-sm"
                                placeholder="nama@email.com" />
                        </div>
                        @error('email')
                            <p class="text-sm text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-slate-700 block">Password</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-sky-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password"
                                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-800 placeholder-slate-400 transition-all duration-200 ease-in-out sm:text-sm"
                                placeholder="••••••••" />
                        </div>
                        @error('password')
                            <p class="text-sm text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <!-- Gradient Button: Green -> Dark Blue -->
                        <button type="submit"
                            class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-emerald-500 to-blue-700 hover:from-emerald-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-emerald-100 group-hover:text-white transition-colors"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-500">
                        Butuh bantuan? <a href="https://wa.me/6285694931571"
                            class="font-medium text-sky-600 hover:text-sky-500 transition-colors underline-offset-4 hover:underline">Hubungi
                            Admin</a>
                    </p>
                </div>
            </div>

            <p class="text-center text-xs text-slate-500 mt-8">
                &copy; {{ date('Y') }} Absensi App. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
