<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dossier Patient') }} #{{ $patient->id }}
            </h2>
            <a href="{{ route('patients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                &larr; Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl font-bold text-gray-500 uppercase">
                                {{ substr($patient->last_name, 0, 1) }}{{ substr($patient->first_name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-gray-800">{{ $patient->last_name }}
                                    {{ $patient->first_name }}</h3>
                                <p class="text-gray-500">{{ $patient->gender }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Âge :</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}
                                    ans</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Né(e) le :</span>
                                <span
                                    class="font-semibold">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-500">Téléphone :</span>
                                <span class="font-semibold">{{ $patient->phone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-gray-500">Créé le :</span>
                                <span>{{ $patient->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-2">
                            <a href="{{ route('patients.edit', $patient->id) }}"
                                class="flex-1 bg-gray-100 text-gray-700 text-center py-2 rounded hover:bg-gray-200 text-sm">
                                Modifier
                            </a>
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Confirmer la suppression ?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-50 text-red-600 py-2 rounded hover:bg-red-100 text-sm">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div
                    class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-purple-500">
                    <div class="p-6 h-full flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Contexte Clinique (Facteurs de risque)
                        </h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="block text-xs text-gray-500 uppercase">Type de Diabète</span>
                                <span
                                    class="font-bold text-lg text-gray-800">{{ $patient->diabetes_type ?? 'Non spécifié' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <span class="block text-xs text-gray-500 uppercase">Année Diagnostic</span>
                                @if ($patient->diagnosis_year)
                                    <span class="font-bold text-lg text-gray-800">{{ $patient->diagnosis_year }}</span>
                                    <span class="text-xs text-gray-500">({{ date('Y') - $patient->diagnosis_year }} ans
                                        d'ancienneté)</span>
                                @else
                                    <span class="text-gray-400 italic">Non renseigné</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex-grow">
                            <span class="block text-xs text-gray-500 uppercase mb-1">Antécédents / Observations</span>
                            <div
                                class="bg-gray-50 p-3 rounded h-full text-sm text-gray-700 italic border border-gray-100">
                                {{ $patient->medical_history ?? 'Aucune note particulière enregistrée pour ce patient.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mb-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg overflow-hidden text-white">
                <div class="p-8 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Nouvel Examen Rétinien</h2>
                        <p class="text-indigo-100 max-w-xl">
                            Lancez une analyse par Intelligence Artificielle pour détecter les signes de rétinopathie
                            diabétique (Microanévrismes, exsudats, hémorragies).
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <a href="{{ route('scans.create', $patient->id) }}""
                            class="inline-flex items-center bg-white text-indigo-700 font-bold py-3 px-6 rounded-full shadow hover:bg-gray-100 transform hover:scale-105 transition">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Scanner l'Oeil
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Historique des Analyses</h3>

                    @if ($patient->scans->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded border border-dashed border-gray-300">
                            <p class="text-gray-500">Aucun examen enregistré pour ce patient.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($patient->scans as $scan)
                                <div
                                    class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition bg-white relative group">

                                    <div class="h-48 bg-gray-100 relative group cursor-pointer">
                                        <a href="{{ route('scans.show', $scan->id) }}">
                                            <img src="{{ asset('storage/' . $scan->image_path) }}" alt="Scan"
                                                class="w-full h-full object-cover transition duration-300 group-hover:opacity-75">

                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                                <span
                                                    class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">Voir
                                                    Détails</span>
                                            </div>
                                        </a>

                                        <span
                                            class="absolute top-2 right-2 bg-gray-900 text-white text-xs font-bold px-2 py-1 rounded opacity-75">
                                            {{ $scan->eye_side }}
                                        </span>
                                    </div>

                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">
                                            {{ $scan->created_at->format('d/m/Y à H:i') }}</p>
                                        <div class="font-bold text-gray-800 mb-2">
                                            @if ($scan->final_diagnosis)
                                                <span class="text-green-600">{{ $scan->final_diagnosis }}</span>
                                            @elseif($scan->ai_result)
                                                <span class="text-indigo-600">{{ $scan->ai_result }}</span>
                                            @else
                                                <span class="text-gray-400 italic">Analyse en cours...</span>
                                            @endif
                                        </div>

                                        <div class="mt-4 flex justify-between items-center pt-2 border-t">
                                            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                                {{ $scan->status }}
                                            </span>

                                            <form action="{{ route('scans.destroy', $scan->id) }}" method="POST"
                                                onsubmit="return confirm('Attention : L\'image sera définitivement supprimée du serveur. Confirmer ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
