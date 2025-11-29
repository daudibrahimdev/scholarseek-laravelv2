<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // Untuk bikin slug

class ScholarshipCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ScholarshipCategory::orderBy('name')->get();
        return view('admin.scholarship.category', compact('categories'));
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
        $request->validate([
            'name' => 'required|unique:scholarship_categories|max:100',
        ]);

        ScholarshipCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Otomatis membuat slug dari nama
            'description' => $request->description, // Jika kamu tambahkan description di form
            'is_active' => $request->has('is_active'), // Mengambil boolean dari checkbox (optional)
        ]);

        return redirect()->route('admin.scholarship.categories.index')
                         ->with('success', 'Kategori beasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScholarshipCategory $scholarshipCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScholarshipCategory $scholarshipCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScholarshipCategory $scholarshipCategory)
    {
        $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('scholarship_categories')->ignore($scholarshipCategory->id),
            ],
        ]);
        
        $scholarshipCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Update slug jika nama berubah
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.scholarship.categories.index')
                         ->with('success', 'Kategori beasiswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScholarshipCategory $scholarshipCategory)
    {
        $scholarshipCategory->delete();

        return redirect()->route('admin.scholarship.categories.index')
                         ->with('success', 'Kategori beasiswa berhasil dihapus!');
    }
}
