<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RetinaScan - Dépistage Assisté par IA</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'sans-serif'],
                        },
                        colors: {
                            medical: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                500: '#3b82f6', // Bleu standard
                                600: '#2563eb', // Bleu action
                                700: '#1d4ed8', // Bleu foncé
                                900: '#1e3a8a', // Bleu nuit
                            }
                        }
                    }
                }
            }
        </script>
    @endif
</head>

<body class="font-sans antialiased text-gray-800 bg-white selection:bg-blue-500 selection:text-white">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer group" onclick="window.scrollTo(0,0)">
                    <div class="bg-blue-600 p-2 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-gray-900">Retina<span
                            class="text-blue-600">Scan</span></span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#home"
                        class="text-gray-600 hover:text-blue-600 transition font-medium text-sm uppercase tracking-wide">Accueil</a>
                    <a href="#solution"
                        class="text-gray-600 hover:text-blue-600 transition font-medium text-sm uppercase tracking-wide">La
                        Solution</a>
                    <a href="#about"
                        class="text-gray-600 hover:text-blue-600 transition font-medium text-sm uppercase tracking-wide">À
                        Propos</a>
                    <a href="#contact"
                        class="text-gray-600 hover:text-blue-600 transition font-medium text-sm uppercase tracking-wide">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-5 py-2.5 bg-blue-600 text-white rounded-full text-sm font-bold hover:bg-blue-700 transition shadow-md flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                                Mon Tableau de Bord
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-gray-700 hover:text-blue-600 transition px-4">Connexion</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="hidden sm:inline-block px-5 py-2.5 bg-gray-900 text-white rounded-full text-sm font-bold hover:bg-black transition shadow-md">
                                    Inscription Médecin
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="home"
        class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest mb-6">
                    Nouvelle Version 2.0 avec IA
                </div>
                <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-gray-900 mb-6 leading-tight">
                    L'IA au service de la <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Santé
                        Visuelle</span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 mb-10 leading-relaxed">
                    RetinaScan utilise des modèles de <strong>Deep Learning</strong> avancés pour assister les
                    ophtalmologistes dans la détection précoce de la <strong>rétinopathie diabétique</strong>.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-8 py-4 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 transition shadow-xl hover:shadow-blue-500/30 flex items-center justify-center">
                            Accéder aux Analyses
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-4 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 transition shadow-xl hover:shadow-blue-500/30 flex items-center justify-center">
                            Commencer le Diagnostic
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="#solution"
                            class="px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-xl font-bold text-lg hover:bg-gray-50 transition flex items-center justify-center">
                            Comment ça marche ?
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none opacity-40">
            <div
                class="absolute top-20 left-10 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-20 right-10 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-32 left-1/2 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>
    </section>

    <section id="solution" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Une technologie de pointe</h2>
                <p class="mt-4 text-xl text-gray-500">Un workflow optimisé pour les médecins, du scan au rapport PDF.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div
                    class="group relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analyse d'Images</h3>
                    <p class="text-gray-600 leading-relaxed">Téléchargez un fond d'œil et obtenez en quelques secondes
                        une classification du stade de la maladie (0 à 4) avec un score de confiance.</p>
                </div>

                <div
                    class="group relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rapports Médicaux</h3>
                    <p class="text-gray-600 leading-relaxed">Générez automatiquement des rapports PDF professionnels,
                        incluant le diagnostic, l'image analysée et votre signature électronique.</p>
                </div>

                <div
                    class="group relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-cyan-100 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Données Sécurisées</h3>
                    <p class="text-gray-600 leading-relaxed">Chaque médecin dispose de son propre espace sécurisé. Les
                        dossiers patients sont isolés et protégés contre les accès non autorisés.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-blue-50/50 skew-x-12 translate-x-20 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-blue-600 font-bold tracking-wide uppercase text-sm mb-3">Pathologie & Enjeux</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Qu'est-ce que la Rétinopathie Diabétique
                    ?</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Première cause de cécité prévisible dans le monde, cette complication du diabète affecte les
                    vaisseaux sanguins de la rétine.
                    <span class="font-bold text-blue-700">Sans dépistage précoce, les lésions sont
                        irréversibles.</span>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-24">

                <div class="relative group">
                    <div
                        class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl opacity-20 group-hover:opacity-40 blur transition duration-500">
                    </div>
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                        <div class="grid grid-cols-2 h-80">
                            <div class="relative h-full border-r-2 border-white">
                                <img src="{{ asset('storage/imageSaine.png') }}" alt="Rétine Saine"
                                    class="absolute inset-0 w-full h-full object-cover">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-green-600/90 text-white text-center py-2 text-sm font-bold backdrop-blur-sm">
                                    ✓ Rétine Saine
                                </div>
                            </div>
                            <div class="relative h-full">
                                <img src="{{ asset('storage/image.png') }}" alt="Rétine Malade"
                                    class="absolute inset-0 w-full h-full object-cover hue-rotate-15 contrast-125">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-red-600/90 text-white text-center py-2 text-sm font-bold backdrop-blur-sm">
                                    ⚠ Rétinopathie Avancée
                                </div>

                                <div class="absolute top-1/3 left-1/4 w-4 h-4 bg-red-500 rounded-full animate-ping">
                                </div>
                                <div
                                    class="absolute top-1/3 left-1/4 w-4 h-4 bg-red-500 border-2 border-white rounded-full">
                                </div>

                                <div
                                    class="absolute bottom-1/3 right-1/3 w-3 h-3 bg-red-500 rounded-full animate-ping delay-700">
                                </div>
                                <div
                                    class="absolute bottom-1/3 right-1/3 w-3 h-3 bg-red-500 border-2 border-white rounded-full">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center italic">Simulation visuelle des lésions
                        rétiniennes (microanévrismes et hémorragies).</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mt-1">
                            1</div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-900">Hyperglycémie Chronique</h4>
                            <p class="text-gray-600 mt-1">L'excès de sucre dans le sang fragilise la paroi des
                                capillaires rétiniens, entraînant une perte d'étanchéité.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold mt-1">
                            2</div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-900">Hémorragies & Exsudats</h4>
                            <p class="text-gray-600 mt-1">Des micro-saignements et des dépôts de lipides apparaissent
                                (les taches jaunes et rouges détectées par notre IA).</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center text-white font-bold mt-1">
                            3</div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-900">Risque de Cécité</h4>
                            <p class="text-gray-600 mt-1">Sans traitement, de nouveaux vaisseaux fragiles se forment
                                (prolifération), menant au décollement de la rétine.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-8 md:p-12 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Les 5 Stades détectés par RetinaScan</h3>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm text-center border-b-4 border-green-500">
                        <div class="text-lg font-bold text-gray-900 mb-1">Stade 0</div>
                        <div class="text-xs font-bold text-green-600 uppercase mb-2">Absence</div>
                        <p class="text-xs text-gray-500">Rétine normale, aucun signe visible.</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm text-center border-b-4 border-blue-400">
                        <div class="text-lg font-bold text-gray-900 mb-1">Stade 1</div>
                        <div class="text-xs font-bold text-blue-500 uppercase mb-2">Légère</div>
                        <p class="text-xs text-gray-500">Présence de microanévrismes isolés.</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm text-center border-b-4 border-yellow-400">
                        <div class="text-lg font-bold text-gray-900 mb-1">Stade 2</div>
                        <div class="text-xs font-bold text-yellow-600 uppercase mb-2">Modérée</div>
                        <p class="text-xs text-gray-500">Hémorragies plus nombreuses.</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm text-center border-b-4 border-orange-500">
                        <div class="text-lg font-bold text-gray-900 mb-1">Stade 3</div>
                        <div class="text-xs font-bold text-orange-600 uppercase mb-2">Sévère</div>
                        <p class="text-xs text-gray-500">Signes pré-proliférants multiples.</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm text-center border-b-4 border-red-600">
                        <div class="text-lg font-bold text-gray-900 mb-1">Stade 4</div>
                        <div class="text-xs font-bold text-red-600 uppercase mb-2">Proliférante</div>
                        <p class="text-xs text-gray-500">Néovaisseaux, risque imminent.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <span class="block text-4xl font-extrabold text-blue-600">463 M</span>
                    <span class="text-sm text-gray-500 font-medium">Diabétiques dans le monde</span>
                </div>
                <div>
                    <span class="block text-4xl font-extrabold text-blue-600">1 sur 3</span>
                    <span class="text-sm text-gray-500 font-medium">Développera une rétinopathie</span>
                </div>
                <div>
                    <span class="block text-4xl font-extrabold text-blue-600">95%</span>
                    <span class="text-sm text-gray-500 font-medium">Évitable si détecté tôt</span>
                </div>
            </div>

        </div>
    </section>

    <section id="contact" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact & Support</h2>
                    <p class="text-gray-600 mb-8 text-lg">
                        Vous êtes un établissement de santé ou un praticien et souhaitez intégrer RetinaScan ? Notre
                        équipe est à votre disposition.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Email</h4>
                                <p class="text-gray-600">contact@retinascan.med</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Adresse</h4>
                                <p class="text-gray-600">Pôle Technologique Santé, Casablanca</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
                    <form action="#" class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nom du Médecin /
                                Clinique</label>
                            <input type="text" id="name"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="Dr. ...">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email
                                Professionnel</label>
                            <input type="email" id="email"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="contact@clinique.com">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                            <textarea id="message" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="Demande de démo..."></textarea>
                        </div>

                        <button type="button"
                            class="w-full py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition transform hover:-translate-y-0.5 shadow-lg">
                            Envoyer la demande
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-white py-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center gap-2">
                    <div class="bg-blue-600 p-1.5 rounded-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-gray-900">RetinaScan</span>
                </div>
                <div class="flex space-x-8 text-sm font-medium">
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">Mentions légales</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">Confidentialité Données
                        Santé</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">Support</a>
                </div>
            </div>
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} RetinaScan Project. Tous droits réservés.
            </div>
        </div>
    </footer>

</body>

</html>
