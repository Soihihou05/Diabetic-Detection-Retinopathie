<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index2()
    {
        //
        $totPatients = Patient::where('user_id', Auth::id())->count();
        $patients = Patient::where('user_id', Auth::id())->latest()->paginate(20);
        return view('patients.index', compact('patients', 'totPatients'));
    }
    public function index()
    {
        //
        $totPatients = Patient::where('user_id', Auth::id())->count();
        $patients = Patient::where('user_id', Auth::id())->latest()->paginate(5);
        return view('dashboard', compact('patients', 'totPatients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            // On rend ces champs 'nullable' au cas où le médecin ne sait pas, 
            // mais ils sont présents dans la requête
            'diabetes_type' => 'nullable|string',
            'diagnosis_year' => 'nullable|integer',
            'medical_history' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // On lie le patient à l'utilisateur connecté
        $data = $request->all();
        $data['user_id'] = Auth::id();

        Patient::create($data);

        return redirect()->route('patients.index')
            ->with('success', 'Patient ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
        $this->authorizeAccess($patient);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
        $this->authorizeAccess($patient);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
        $this->authorizeAccess($patient);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            // On rend ces champs 'nullable' au cas où le médecin ne sait pas, 
            // mais ils sont présents dans la requête
            'diabetes_type' => 'nullable|string',
            'diagnosis_year' => 'nullable|integer',
            'medical_history' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Dossier mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
        $this->authorizeAccess($patient);
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé.');
    }

    private function authorizeAccess($patient)
    {
        if ($patient->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
