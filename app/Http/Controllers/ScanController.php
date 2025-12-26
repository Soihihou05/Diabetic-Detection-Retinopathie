<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $aiResult = null;
        $aiConfidence = null;

        try {
            // On récupère le contenu brut du fichier pour l'envoyer
            $fileContent = file_get_contents(storage_path('app/public/' . $path));

            // On envoie une requête POST à notre script Python (port 5001)
            $response = Http::attach(
                'file',
                $fileContent,
                $fileName
            )->post('http://127.0.0.1:5001/predict');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success']) {
                    $aiResult = $data['result'];       // Ex: "Stade 2 : Modérée"
                    $aiConfidence = $data['confidence']; // Ex: 98.5
                }
            }
        } catch (\Exception $e) {
            // Si le serveur Python est éteint, on ne fait pas planter Laravel,
            // on enregistre juste sans résultat IA.
            // Tu pourras ajouter un log ici : Log::error($e->getMessage());
        }

        // 4. Création en Base de Données (avec les résultats IA !)
        $scan = Scan::create([
            'patient_id' => $patient->id,
            'eye_side' => $request->eye_side,
            'image_path' => $path,
            'ai_result' => $aiResult,          // Rempli automatiquement
            'ai_confidence' => $aiConfidence,  // Rempli automatiquement
            'status' => $aiResult ? 'brouillon' : 'erreur_ia', // Petit bonus statut
        ]);

        $message = $aiResult
            ? 'Analyse IA terminée : ' . $aiResult
            : 'Image enregistrée, mais le serveur IA ne répondait pas.';

        return redirect()->route('scans.show', $scan->id) // On redirige direct vers le résultat
            ->with('success', $message);
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

    // RELANCER L'ANALYSE IA MANUELLEMENT
    public function analyze(Scan $scan)
    {
        // 1. Sécurité
        if ($scan->patient->user_id !== Auth::id()) abort(403);

        // 2. Vérifier que le fichier existe toujours
        if (!Storage::disk('public')->exists($scan->image_path)) {
            return back()->with('error', 'Le fichier image est introuvable sur le serveur.');
        }

        // 3. Préparer l'appel à Python
        $aiResult = null;
        $aiConfidence = null;
        $message = "Erreur : Le serveur IA ne répond pas.";

        try {
            // On récupère le fichier existant
            $fullPath = storage_path('app/public/' . $scan->image_path);
            $fileContent = file_get_contents($fullPath);

            // On retrouve le nom du fichier (juste pour l'envoi)
            $fileName = basename($scan->image_path);

            // Appel API
            $response = Http::attach(
                'file',
                $fileContent,
                $fileName
            )->post('http://127.0.0.1:5001/predict');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success']) {
                    $aiResult = $data['result'];
                    $aiConfidence = $data['confidence'];
                    $message = "Nouvelle analyse réussie : " . $aiResult;
                }
            }
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            $message = "Erreur technique : Vérifiez que le script Python est lancé.";
        }

        // 4. Mise à jour seulement si on a un résultat
        if ($aiResult) {
            $scan->update([
                'ai_result' => $aiResult,
                'ai_confidence' => $aiConfidence,
                'status' => 'brouillon' // On repasse en brouillon pour validation
            ]);
            return back()->with('success', $message);
        } else {
            // Si échec, on met le statut erreur
            $scan->update(['status' => 'erreur_ia']);
            return back()->with('error', $message);
        }
    }
    // CHANGER L'IMAGE D'UN SCAN EXISTANT
    public function updateImage(Request $request, Scan $scan)
    {
        // 1. Sécurité
        if ($scan->patient->user_id !== Auth::id()) abort(403);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // 2. Supprimer l'ancienne image physique
        if (Storage::disk('public')->exists($scan->image_path)) {
            Storage::disk('public')->delete($scan->image_path);
        }

        // 3. Préparer le nouveau nom (Même logique que le store)
        $cleanName = Str::slug($scan->patient->last_name . '-' . $scan->patient->first_name);
        $typeDiabete = Str::slug($scan->patient->diabetes_type ?? 'inconnu');
        $eye = $scan->eye_side; // On garde le même œil
        $timestamp = time();
        $extension = $request->file('image')->getClientOriginalExtension();

        $fileName = "{$cleanName}_{$eye}_{$typeDiabete}_{$timestamp}.{$extension}";

        // 4. Enregistrer la nouvelle image
        $path = $request->file('image')->storeAs('scans', $fileName, 'public');

        // 5. Mettre à jour la BDD et RESET L'IA
        $scan->update([
            'image_path' => $path,
            'ai_result' => null,       // On efface l'ancien résultat
            'ai_confidence' => null,   // On efface l'ancienne confiance
            'status' => 'brouillon'    // On remet le statut à zéro
        ]);

        return back()->with('success', 'Image remplacée. Veuillez relancer l\'analyse IA.');
    }

    //Generation du rapport, format PDF
    public function downloadPdf(Request $request, Scan $scan)
    {
        // 1. Sécurité
        if ($scan->patient->user_id !== Auth::id()) abort(403);

        // 2. Préparation des données
        //  Validation : on vérifie juste qu'on a reçu le dessin
        $request->validate(['signature' => 'required']);

        // On récupère directement le code Base64 (ex: "data:image/png;base64,iVBOR...")
        // C'est ça qui contient l'image. On ne le stocke pas, on le passe à la vue.
        $signatureData = $request->input('signature');
        //////////////////////////////////////////////////////////
        $cleanName = Str::slug($scan->patient->last_name . '-' . $scan->patient->first_name);
        $typeDiabete = Str::slug($scan->patient->diabetes_type ?? 'inconnu');
        $eye = $scan->eye_side; // On garde le même œil
        $timestamp = time();

        $fileName = "{$cleanName}_{$eye}_{$typeDiabete}_{$timestamp}.pdf";
        $data = [
            'scan' => $scan,
            'patient' => $scan->patient,
            'doctor' => Auth::user(),
            'date' => now()->format('d/m/Y'),
            // Astuce pour l'image en PDF : convertir en base64 pour éviter les bugs de chemin
            'imagePath' => public_path('storage/' . $scan->image_path),
            'signatureData' => $signatureData,
        ];

        // 3. Génération du PDF
        $pdf = Pdf::loadView('scans.pdf', $data);

        // Optionnel : Configurer le format papier
        $pdf->setPaper('A4', 'portrait');

        // 4. Téléchargement direct
        return $pdf->download('Rapport_Medical_' . $fileName);
    }
}
