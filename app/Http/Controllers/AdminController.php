<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; 

use App\Models\DocumentCategory;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
 
        if(auth::id())
        {
            $userRole = Auth::user()->role;

            if($userRole == 'admin')
            {
                return view('admin.dashboard.index');
            }
            else if($userRole == 'mentee')
            {
                return redirect()->route('mentee.index');
            }
            else if($userRole == 'mentor')
            {
                return redirect()->route('mentor.index');
            }
        } 
        else
        {
            return redirect('login');
        }
        
    }
    public function kategori_dokumen()
    {
        return view('admin.documents.category');
    }

    public function tambah_kategori(Request $request)
    {
        
        
    }
}


