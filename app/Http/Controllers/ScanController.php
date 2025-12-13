<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        //
        return view('scans.create', compact('patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'eye_side' => 'required|in:OD,OG',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        // 1. Sauvegarder l'image dans le dossier "storage/app/public/scans"
        $cleanName = Str::slug($patient->last_name . '-' . $patient->first_name);

        //On recupere le type de Diabete
        $typeDiabete = Str::slug($patient->diabetes_type ?? 'inconnu');

        // On ajoute l'œil (OD/OG)
        $eye = $request->eye_side;

        // On ajoute le timestamp (l'heure exacte) pour éviter les doublons si on scanne 2 fois
        $timestamp = time();

        // On récupère l'extension originale (.jpg, .png)
        $extension = $request->file('image')->getClientOriginalExtension();

        // Résultat : "dupont-jean_OD_Type2_170245899.jpg"
        $fileName = "{$cleanName}_{$eye}_{$typeDiabete}_{$timestamp}.{$extension}";

        // 2. ENREGISTREMENT AVEC 'storeAs'
        // Au lieu de 'store' (qui génère un nom aléatoire), on utilise 'storeAs'
        $path = $request->file('image')->storeAs('scans', $fileName, 'public');

        // 2. Créer l'entrée dans la base de données
        $scan = Scan::create([
            'patient_id' => $patient->id,
            'eye_side' => $request->eye_side,
            'image_path' => $path,
            'status' => 'brouillon', // En attente de l'IA
        ]);

        // --- ICI VIENDRA LE CODE POUR LANCER L'IA PYTHON PLUS TARD ---
        // Exemple : PythonService::analyze($scan->id);

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Image uploadée avec succès. L\'analyse IA va démarrer.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scan $scan)
    {
        //
        if ($scan->patient->user_id !== Auth::id()) abort(403);

        return view('scans.show', compact('scan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scan $scan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scan $scan)
    {
        //
        if ($scan->patient->user_id !== Auth::id()) abort(403);

        $request->validate([
            'final_diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
        ]);

        $scan->update([
            'final_diagnosis' => $request->final_diagnosis,
            'prescription' => $request->prescription,
            'doctor_notes' => $request->doctor_notes,
            'status' => 'valide' // Le dossier passe de "brouillon" à "valide"
        ]);

        return redirect()->route('scans.show', $scan->id)
                         ->with('success', 'Diagnostic validé et enregistré.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scan $scan)
    {
        //
        // 1. SÉCURITÉ : Vérifier que ce scan appartient bien à un patient du médecin connecté
        if ($scan->patient->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // 2. SUPPRESSION DU FICHIER IMAGE
        // On vérifie si le fichier existe sur le disque 'public' avant de le supprimer
        if ($scan->image_path && Storage::disk('public')->exists($scan->image_path)) {
            Storage::disk('public')->delete($scan->image_path);
        }

        // 3. SUPPRESSION EN BASE DE DONNÉES
        $scan->delete();

        // 4. RETOUR AU DOSSIER PATIENT
        return redirect()->route('patients.show', $scan->patient_id)
            ->with('success', 'Scan et image supprimés définitivement.');
    }
}
