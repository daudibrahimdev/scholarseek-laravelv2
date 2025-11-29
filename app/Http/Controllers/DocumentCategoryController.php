<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data kategori dari database, diurutkan berdasarkan nama
        $categories = DocumentCategory::orderBy('name')->get();
        
        // return ke view dengan data kategori
        return view('admin.documents.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            // 'name' wajib, unik di tabel document_categories dan maks 100 karakter
            'name' => 'required|unique:document_categories|max:100',
        ], [
            'name.required' => 'Nama kategori dokumen wajib diisi.',
            'name.unique' => 'Nama kategori dokumen sudah ada.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        // 2. Simpan ke Database
        DocumentCategory::create([
            'name' => $request->name,
        ]);

        // 3. Redirect dengan Notifikasi Sukses
        return redirect()->route('admin.document.categories.index')
                         ->with('success', 'Kategori dokumen berhasil ditambahkan!');
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
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        // 1. Validasi Input
        $request->validate([
            // 'name' wajib, dan unik KECUALI jika namanya sama dengan nama kategori saat ini
            'name' => [
                'required',
                'max:100',
                Rule::unique('document_categories')->ignore($documentCategory->id),
            ],
        ], [
            'name.required' => 'Nama kategori dokumen wajib diisi.',
            'name.unique' => 'Nama kategori dokumen sudah ada.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        // 2. Perbarui Data
        $documentCategory->update([
            'name' => $request->name,
        ]);

        // 3. Redirect dengan Notifikasi Sukses
        return redirect()->route('admin.document.categories.index')
                         ->with('success', 'Kategori dokumen berhasil diperbarui!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $documentCategory)
    {
        // Hapus data dari database
        $documentCategory->delete();

        // Redirect dengan Notifikasi Sukses
        return redirect()->route('admin.document.categories.index')
                         ->with('success', 'Kategori dokumen berhasil dihapus!');
    }
}
