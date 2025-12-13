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
}
