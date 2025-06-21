<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ScrumGUinee - Plateforme Agile Moderne</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            guinee: {
                                green: '#009543',
                                yellow: '#FCD116',
                                red: '#CE1126',
                            }
                        }
                    }
                }
            }
        </script>
        <style>
            .hero-bg {
                background: linear-gradient(120deg, #232946 0%, #3b82f6 60%, #764ba2 100%);
            }
            .badge-guinee {
                background: linear-gradient(90deg, #CE1126 33%, #FCD116 33%, #FCD116 66%, #009543 66%);
                color: #fff;
                font-weight: bold;
                letter-spacing: 1px;
            }
            .floating {
                animation: floating 3s ease-in-out infinite;
            }
            @keyframes floating {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
        </style>
    </head>
    <body class="bg-gray-950 text-white font-sans">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 bg-gray-950/90 backdrop-blur-md border-b border-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center badge-guinee shadow-lg">
                            <span class="text-lg">SG</span>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            ScrumGUinee
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">
                                Connexion
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                    Créer un compte
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-bg min-h-screen flex items-center justify-center pt-24 pb-12 relative overflow-hidden">
            <div class="absolute top-10 left-10 w-24 h-24 bg-guinee-green/20 rounded-full floating"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-guinee-yellow/20 rounded-full floating" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 left-20 w-12 h-12 bg-guinee-red/20 rounded-full floating" style="animation-delay: 2s;"></div>
            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <span class="inline-block badge-guinee px-4 py-1 rounded-full mb-4 shadow">Made in Guinée</span>
                <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                    Gérez vos projets <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Agile</span><br>
                    <span class="text-white">simplement et efficacement</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-2xl mx-auto">
                    La plateforme moderne pour piloter vos équipes, vos tâches et vos sprints, pensée pour les réalités africaines et la réussite de vos projets.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-200 shadow-lg">
                            Accéder au Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-200 shadow-lg">
                            Commencer gratuitement
                        </a>
                        <a href="{{ route('login') }}" class="border border-gray-400 hover:border-gray-200 text-gray-200 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-200">
                            Se connecter
                        </a>
                    @endauth
                </div>
                <!-- Illustration stylisée -->
                {{-- <div class="flex justify-center">
                    <img src="https://svgshare.com/i/15kA.svg" alt="Scrum Board Illustration" class="w-full max-w-xl rounded-xl shadow-2xl border-4 border-gray-900" loading="lazy">
                </div> --}}
            </div>
        </section>

        <!-- Pourquoi ScrumGUinee -->
        <section class="py-16 bg-gray-900">
            <div class="max-w-5xl mx-auto px-4">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-10">Pourquoi choisir <span class="badge-guinee px-2 py-1 rounded">ScrumGUinee</span> ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-gray-800 rounded-xl p-6 flex flex-col items-center text-center shadow hover:scale-105 transition-transform">
                        <span class="text-4xl mb-2">🤝</span>
                        <h3 class="text-xl font-semibold mb-2">Collaboration en temps réel</h3>
                        <p class="text-gray-400">Travaillez ensemble, commentez, partagez et avancez en équipe, où que vous soyez.</p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 flex flex-col items-center text-center shadow hover:scale-105 transition-transform">
                        <span class="text-4xl mb-2">📊</span>
                        <h3 class="text-xl font-semibold mb-2">Statistiques avancées</h3>
                        <p class="text-gray-400">Suivez la progression, la vélocité et les indicateurs clés de vos projets.</p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 flex flex-col items-center text-center shadow hover:scale-105 transition-transform">
                        <span class="text-4xl mb-2">🔒</span>
                        <h3 class="text-xl font-semibold mb-2">Sécurité & confidentialité</h3>
                        <p class="text-gray-400">Vos données sont protégées et hébergées localement, dans le respect de la confidentialité.</p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 flex flex-col items-center text-center shadow hover:scale-105 transition-transform">
                        <span class="text-4xl mb-2">🌍</span>
                        <h3 class="text-xl font-semibold mb-2">Pensé pour l'Afrique</h3>
                        <p class="text-gray-400">Une solution adaptée aux besoins et à la culture de gestion de projet en Guinée et en Afrique.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fonctionnalités principales -->
        <section class="py-20 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Fonctionnalités principales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Gestion des Tâches</h3>
                        <p class="text-gray-400">Créez, assignez et suivez vos tâches avec un système de priorités et de statuts avancé.</p>
                    </div>
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Sprints & Planning</h3>
                        <p class="text-gray-400">Planifiez vos sprints, estimez les story points et suivez la vélocité de votre équipe.</p>
                    </div>
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Gestion d'Équipe</h3>
                        <p class="text-gray-400">Gérez les membres de votre équipe, leurs rôles et permissions avec une interface intuitive.</p>
                    </div>
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Analytics & Rapports</h3>
                        <p class="text-gray-400">Visualisez les métriques de votre projet avec des graphiques et burndown charts interactifs.</p>
                    </div>
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Communication</h3>
                        <p class="text-gray-400">Chat intégré pour la communication d'équipe et les commentaires sur les tâches.</p>
                    </div>
                    <div class="bg-gray-700 rounded-xl p-6 group hover:bg-gray-600 transition-all duration-200">
                        <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Sécurité</h3>
                        <p class="text-gray-400">Authentification sécurisée et gestion des permissions pour protéger vos données.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section class="py-16 bg-gray-900">
            <div class="max-w-4xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-10">Ils utilisent ScrumGUinee</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-800 rounded-xl p-6 shadow text-center">
                        <p class="text-gray-200 italic mb-4">"Enfin une plateforme adaptée à notre façon de travailler ! L'interface est claire et l'équipe a gagné en efficacité."</p>
                        <div class="flex flex-col items-center">
                            <span class="font-bold text-guinee-green">Fatoumata D.</span>
                            <span class="text-gray-400 text-sm">Chef de projet, Conakry</span>
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 shadow text-center">
                        <p class="text-gray-200 italic mb-4">"Le suivi des sprints et la gestion d'équipe sont top. On recommande à toutes les startups guinéennes !"</p>
                        <div class="flex flex-col items-center">
                            <span class="font-bold text-guinee-yellow">Mamadou S.</span>
                            <span class="text-gray-400 text-sm">CTO, Labé</span>
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 shadow text-center">
                        <p class="text-gray-200 italic mb-4">"Simple, rapide, efficace. On a adopté ScrumGUinee pour tous nos projets IT."</p>
                        <div class="flex flex-col items-center">
                            <span class="font-bold text-guinee-red">Aïssatou B.</span>
                            <span class="text-gray-400 text-sm">Product Owner, Kindia</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Commencer en 3 étapes -->
        <section class="py-16 bg-gray-800">
            <div class="max-w-4xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-10">Commencez en 3 étapes</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-guinee-green flex items-center justify-center text-3xl mb-4">1</div>
                        <h3 class="font-semibold mb-2">Créer un compte</h3>
                        <p class="text-gray-400">Inscrivez-vous gratuitement en quelques secondes.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-guinee-yellow flex items-center justify-center text-3xl mb-4">2</div>
                        <h3 class="font-semibold mb-2">Créer un projet</h3>
                        <p class="text-gray-400">Lancez votre premier projet et invitez votre équipe.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-guinee-red flex items-center justify-center text-3xl mb-4">3</div>
                        <h3 class="font-semibold mb-2">Piloter & collaborer</h3>
                        <p class="text-gray-400">Gérez vos tâches, vos sprints et collaborez en temps réel.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-950 border-t border-gray-900">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-8">
                    <div class="flex items-center space-x-2 mb-4 md:mb-0">
                        <div class="w-8 h-8 rounded-lg badge-guinee flex items-center justify-center">
                            <span class="text-lg">SG</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            ScrumGUinee
                        </span>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Documentation</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Support</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="#" class="hover:scale-110 transition-transform"><img src="https://upload.wikimedia.org/wikipedia/commons/e/e6/Flag_of_Guinea.svg" alt="Guinée" class="w-7 h-5 rounded shadow"></a>
                        <span class="text-gray-500 text-sm">Conçu en Guinée</span>
                    </div>
                </div>
                <div class="border-t border-gray-900 mt-8 pt-8 text-center text-gray-500">
                    <p>&copy; 2024 ScrumGUinee. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
