<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::with(['category', 'uploader'])->latest()->get();
        $categories = DocumentCategory::all(); // Untuk dropdown
        
        return view('admin.documents.index', compact('documents', 'categories'));
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_category_id' => 'required|exists:document_categories,id', // Cek apakah ID kategori ada
            'document_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt|max:50000', // Max 50MB
        ], [
            'document_file.required' => 'File dokumen wajib diunggah.',
            'document_file.mimes' => 'Format file tidak diizinkan. Gunakan PDF, DOC, PPT, XLS, atau TXT.',
            'document_file.max' => 'Ukuran file tidak boleh melebihi 50MB.'
        ]);

        // 2. Proses Upload File
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            // Membuat nama file unik berdasarkan waktu dan nama asli
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file ke storage/app/public/documents
            $filePath = $file->storeAs('public/documents', $fileName); 
        } else {
            return back()->withInput()->withErrors(['document_file' => 'Gagal memproses file.']);
        }

        // 3. Simpan Data ke Database
        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            // Simpan path relatif (tanpa 'public/') untuk kemudahan akses
            'file_path' => str_replace('public/', '', $filePath), 
            'document_category_id' => $request->document_category_id,
            'uploaded_by' => Auth::id(), // ID user yang sedang login
        ]);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Dokumen "' . $request->title . '" berhasil diunggah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // Kita butuh categories untuk dropdown, dan document yang mau di-edit
    $categories = DocumentCategory::all();
    
    // Biasanya, untuk modal in-place, kita mengembalikan JSON jika menggunakan AJAX, 
    // tapi untuk simpel, kita bisa buat array data.
    return response()->json([
        'document' => $document,
        'categories' => $categories,
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // 1. Validasi Input (Sama seperti Store, tapi file tidak wajib)
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'document_category_id' => 'required|exists:document_categories,id',
        // File tidak wajib (kadang user cuma ganti judul)
        'document_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt|max:50000',
    ], [
        'document_file.mimes' => 'Format file tidak diizinkan.',
        // ... custom messages lainnya ...
    ]);

    $data = $request->only(['title', 'description', 'document_category_id']);
    
    // 2. Proses Penggantian File (Jika ada file baru diunggah)
    if ($request->hasFile('document_file')) {
        // Hapus file lama dari storage
        Storage::delete('public/' . $document->file_path);

        // Upload file baru
        $file = $request->file('document_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/documents', $fileName);
        
        // Update path baru
        $data['file_path'] = str_replace('public/', '', $filePath);
    }
    
    // 3. Update Record di Database
    $document->update($data);

    return redirect()->route('admin.documents.index')
                     ->with('success', 'Dokumen "' . $document->title . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Hapus file dari storage
        Storage::delete('public/' . $document->file_path);

        // Hapus record dari database
        $document->delete();

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Dokumen "' . $document->title . '" berhasil dihapus!');
    }
}
