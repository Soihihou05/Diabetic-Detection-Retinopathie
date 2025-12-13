<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analyse : {{ $scan->patient->last_name }} {{ $scan->patient->first_name }}
            </h2>
            <a href="{{ route('patients.show', $scan->patient_id) }}" class="text-sm text-gray-500 hover:text-gray-900">
                &larr; Retour au dossier patient
            </a>
        </div>
    </x-slot>

    <div class="py-6 h-screen max-h-[90vh]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="flex flex-col lg:flex-row gap-6 h-full">

                <div class="lg:w-2/3 bg-black rounded-lg shadow-2xl overflow-hidden relative flex items-center justify-center group">
                    <img src="{{ asset('storage/' . $scan->image_path) }}" 
                         alt="Fond d'oeil" 
                         class="max-h-full max-w-full object-contain transition-transform duration-500 hover:scale-110 cursor-zoom-in">
                    
                    <div class="absolute top-4 left-4 bg-black/50 text-white px-3 py-1 rounded-full backdrop-blur-sm border border-white/20">
                        {{ $scan->eye_side == 'OD' ? 'Œil Droit' : 'Œil Gauche' }}
                    </div>
                    <div class="absolute bottom-4 left-4 text-gray-400 text-xs">
                        Scanné le {{ $scan->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>

                <div class="lg:w-1/3 flex flex-col gap-6 overflow-y-auto">
                    
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 {{ $scan->ai_result ? 'border-purple-600' : 'border-gray-300' }}">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Analyse Intelligence Artificielle</h3>
                        
                        @if($scan->ai_result)
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl font-bold text-gray-800">{{ $scan->ai_result }}</span>
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">
                                    {{ $scan->ai_confidence }}% Confiance
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Détection automatique basée sur le modèle Deep Learning.</p>
                        @else
                            <div class="flex items-center text-orange-500 animate-pulse">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Analyse en attente...</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Le script Python n'a pas encore traité cette image.</p>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md flex-grow">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Validation Clinique</h3>

                        <form action="{{ route('scans.update', $scan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block font-bold text-gray-700 text-sm mb-2">Diagnostic Final</label>
                                <select name="final_diagnosis" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sélectionner...</option>
                                    <option value="Pas de Rétinopathie" {{ $scan->final_diagnosis == 'Pas de Rétinopathie' ? 'selected' : '' }}>Pas de Rétinopathie (Sain)</option>
                                    <option value="Légère" {{ $scan->final_diagnosis == 'Légère' ? 'selected' : '' }}>Rétinopathie Légère</option>
                                    <option value="Modérée" {{ $scan->final_diagnosis == 'Modérée' ? 'selected' : '' }}>Rétinopathie Modérée</option>
                                    <option value="Sévère" {{ $scan->final_diagnosis == 'Sévère' ? 'selected' : '' }}>Rétinopathie Sévère</option>
                                    <option value="Proliférante" {{ $scan->final_diagnosis == 'Proliférante' ? 'selected' : '' }}>Rétinopathie Proliférante</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block font-bold text-gray-700 text-sm mb-2">Prescription / Traitement</label>
                                <textarea name="prescription" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Laser, injection, surveillance dans 6 mois...">{{ $scan->prescription }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block font-bold text-gray-700 text-sm mb-2">Notes Privées</label>
                                <textarea name="doctor_notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm bg-yellow-50" placeholder="Notes visibles uniquement par vous">{{ $scan->doctor_notes }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-md hover:bg-indigo-700 transition">
                                Valider & Enregistrer
                            </button>
                        </form>
                    </div>

                    @if($scan->status == 'valide')
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-sm text-gray-600 mb-3">Le diagnostic est validé.</p>
                        <button onclick="alert('Nous installerons le générateur PDF à la prochaine étape !')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Télécharger Rapport PDF
                        </button>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>