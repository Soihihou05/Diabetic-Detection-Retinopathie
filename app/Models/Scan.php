<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'eye_side',
        'image_path',
        'ai_result',
        'ai_confidence',
        'final_diagnosis',
        'prescription',
        'doctor_notes',
        'status'
    ];

    // Un scan appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    // ... tes autres méthodes (isCritical, etc.) ...

    /**
     * Retourne une suggestion de traitement basée sur le résultat de l'IA.
     */
    public function getSuggestedTreatmentAttribute()
    {
        $result = $this->ai_result;

        if (!$result) return null;

        // Logique de suggestion (Protocoles standards simplifiés)
        // Tu peux adapter ces textes selon les recommandations médicales réelles
        
        if (str_contains($result, 'Sain')) {
            return "Pas de traitement nécessaire. Contrôle annuel recommandé. Maintien de l'équilibre glycémique et tensionnel.";
        }

        if (str_contains($result, 'Légère')) {
            return "Surveillance du fond d'œil tous les 6 à 12 mois. Renforcement du contrôle diabétique.";
        }

        if (str_contains($result, 'Modérée')) {
            return "Surveillance rapprochée (tous les 3 à 6 mois). Bilan complet des facteurs de risque.";
        }

        if (str_contains($result, 'Sévère')) {
            return "Avis ophtalmologique urgent. Envisager une panphotocoagulation (PPR) au laser selon l'évolution.";
        }

        if (str_contains($result, 'Proliférante')) {
            return "Urgence thérapeutique : Panphotocoagulation (PPR) immédiate ou injections intravitréennes (Anti-VEGF). Vitrectomie si hémorragie.";
        }

        return "Aucune suggestion standard disponible.";
    }
}
