<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ScrumGuiOpt') }}</title>
    
    <!-- Styles Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'slate': {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Styles personnalisés -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .bg-\[#1c1c1e\] {
            background-color: #1c1c1e;
        }
        
        .bg-\[#23272f\] {
            background-color: #23272f;
        }
        
        .bg-\[#232b36\] {
            background-color: #232b36;
        }
        
        .bg-\[#31343b\] {
            background-color: #31343b;
        }
        
        .text-\[#2684ff\] {
            color: #2684ff;
        }
        
        .border-\[#23272f\] {
            border-color: #23272f;
        }
        
        .border-\[#31343b\] {
            border-color: #31343b;
        }
        
        .hover\:border-\[#2684ff\]\/50:hover {
            border-color: rgba(38, 132, 255, 0.5);
        }
        
        .bg-\[#2684ff\]\/20 {
            background-color: rgba(38, 132, 255, 0.2);
        }
        
        .bg-\[#2684ff\]\/10 {
            background-color: rgba(38, 132, 255, 0.1);
        }
        
        .border-\[#2684ff\] {
            border-color: #2684ff;
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#1c1c1e] text-slate-100 min-h-screen">
    <!-- Barre de navigation principale -->
    <nav class="fixed top-0 z-50 w-full bg-[#23272f] border-b border-[#31343b]">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-slate-400 rounded-lg sm:hidden hover:bg-[#232b36] focus:outline-none focus:ring-2 focus:ring-[#2684ff]">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex ms-2 md:me-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">ScrumGuiOpt</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-[#232b36] rounded-full focus:ring-4 focus:ring-[#2684ff]/20" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="{{ Auth::user()->name }}">
                            </button>
                        </div>
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-[#23272f] divide-y divide-[#31343b] rounded-lg shadow" id="user-dropdown">
                            <div class="px-4 py-3">
                                <span class="block text-sm text-white">{{ Auth::user()->name }}</span>
                                <span class="block text-sm text-slate-400 truncate">{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-2" aria-labelledby="user-menu-button">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-[#232b36]">Profil</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-slate-200 hover:bg-[#232b36]">
                                            {{ __('Se déconnecter') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#23272f] border-r border-[#31343b] sm:translate-x-0" aria-label="Sidebar">
        @include('components.sidebar')
    </aside>

    <!-- Contenu principal -->
    <div class="p-4 sm:ml-64 mt-14 bg-[#1c1c1e]">
        <!-- Message flash -->
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-emerald-200 rounded-lg bg-emerald-900/50 border border-emerald-700/50">
                {{ session()->get('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-200 rounded-lg bg-red-900/50 border border-red-700/50">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Contenu principal -->
        <main>
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts pour les menus déroulants et interactions -->
    <script>
        // Fonction pour mettre à jour l'icône du thème
        function updateThemeIcon(isDark) {
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            
            if (isDark) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }

        // Gestion du menu utilisateur
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', () => {
                userDropdown.classList.toggle('hidden');
            });
            
            // Fermer le menu si on clique en dehors
            document.addEventListener('click', (e) => {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
        
        // Gestion du mode sombre/clair
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const html = document.documentElement;
        
        if (darkModeToggle) {
            // Vérifier la préférence utilisateur au chargement
            let isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                           (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            // Appliquer le thème initial
            if (isDarkMode) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            
            // Mettre à jour l'icône en fonction du thème initial
            updateThemeIcon(isDarkMode);
            
            // Gérer le clic sur le bouton de basculement
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                const isDark = html.classList.contains('dark');
                localStorage.setItem('darkMode', isDark);
                updateThemeIcon(isDark);
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>