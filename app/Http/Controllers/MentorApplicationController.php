<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipCategory;
use App\Models\Mentor;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MentorApplicationController extends Controller
{

    /**
     * Tampilkan formulir pendaftaran mentor.
     */
    public function showForm()
    {
        // 1. Cek: Apakah user sudah punya record mentor yang pending/approved?
        $existingMentor = Mentor::where('user_id', Auth::id())->first();

        if ($existingMentor && $existingMentor->verification_status != 'rejected') {
            return redirect('/home')->with('info', 'Anda sudah mendaftar sebagai mentor dengan status: ' . ucfirst($existingMentor->verification_status));
        }

        // 2. Ambil Pilihan Keahlian (gunakan Kategori Beasiswa sebagai pilihan Expertise)
        $expertiseOptions = ScholarshipCategory::where('is_active', true)->get();
        
        return view('onboarding.mentor_register', compact('expertiseOptions', 'existingMentor'));
    }

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input dan File
        $request->validate([
            'bio' => 'required|string|max:5000',
            'domicile_city' => 'required|string|max:100',
            'full_address' => 'nullable|string',
            'phone_number' => 'required|string|max:15',
            'expertise_areas' => 'required|array', // Wajib memilih minimal satu area
            'expertise_areas.*' => 'exists:scholarship_categories,id', 
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:10000', // Max 10MB
            'motivation_letter_file' => 'required|file|mimes:pdf,doc,docx|max:10000', // Max 10MB
        ]);

        // 2. Proses Upload File
        $userName = Auth::user()->name;
        $cvPath = $request->file('cv_file')->storeAs('public/mentor/cv', $userName . '_cv_' . time() . '.pdf');
        $mlPath = $request->file('motivation_letter_file')->storeAs('public/mentor/ml', $userName . '_ml_' . time() . '.pdf');

        // 3. Simpan Data ke Tabel mentors
        Mentor::create([
            'user_id' => Auth::id(),
            'bio' => $request->bio,
            'domicile_city' => $request->domicile_city,
            'full_address' => $request->full_address,
            'phone_number' => $request->phone_number,
            'cv_path' => str_replace('public/', '', $cvPath),
            'motivation_letter_path' => str_replace('public/', '', $mlPath),
            'expertise_areas' => $request->expertise_areas, // Laravel akan konversi array ke JSON
            'verification_status' => 'pending',
            // is_available dan avg_rating akan menggunakan default
        ]);

        return redirect('/home')->with('success', 'Aplikasi mentor Anda berhasil dikirim dan sedang menunggu peninjauan Admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
