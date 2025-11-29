<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipCategory; // Untuk dropdown kategori
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua beasiswa, beserta kategori yang terkait
        $scholarships = Scholarship::with('categories')->latest()->get();
        $categories = ScholarshipCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.scholarship.index', compact('scholarships', 'categories'));
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
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255|unique:scholarships',
            'provider' => 'required|string|max:150',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'link_url' => 'nullable|url|max:2048',
            'category_ids' => 'required|array', // Supaya wajib pilih at least satu kategori
            'category_ids.*' => 'exists:scholarship_categories,id', // Pastikan ID kategori valid
        ]);

        // 2. Gunakan Transaksi untuk memastikan data tersimpan semua (Schol. + Pivot)
        DB::transaction(function () use ($request) {
            
            // Simpan data utama Beasiswa
            $scholarship = Scholarship::create($request->only([
                'title', 'provider', 'description', 'start_date', 'deadline', 'link_url'
            ]));

            // Simpan relasi Many-to-Many menggunakan sync() 
            $scholarship->categories()->sync($request->category_ids);
        });

        return redirect()->route('admin.scholarship.index')
                         ->with('success', 'Beasiswa baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scholarship $scholarship)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scholarship $scholarship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scholarship $scholarship)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                // Rules title unik, kecuali title beasiswa ini sendiri
                Rule::unique('scholarships')->ignore($scholarship->id),
            ],
            'provider' => 'required|string|max:150',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'link_url' => 'nullable|url|max:2048',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:scholarship_categories,id',
        ]);
        
        // 2. Gunakan Transaksi untuk update yang aman
        DB::transaction(function () use ($request, $scholarship) {
            
            // Update data utama Beasiswa
            $scholarship->update($request->only([
                'title', 'provider', 'description', 'start_date', 'deadline', 'link_url'
            ]));

            // Update relasi Many-to-Many
            // sync() akan menghapus relasi lama dan menambahkan yang baru
            $scholarship->categories()->sync($request->category_ids);
        });

        return redirect()->route('admin.scholarship.index')
                         ->with('success', 'Beasiswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        // Hapus semua relasi pivot terkait beasiswa ini
        $scholarship->categories()->detach();

        // Hapus data utama Beasiswa
        $scholarship->delete();

        return redirect()->route('admin.scholarship.index')
                         ->with('success', 'Beasiswa berhasil dihapus!');
    }
}
