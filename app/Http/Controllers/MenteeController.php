<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\UserPackage;
use App\Models\LearningSession;
use App\Models\Package;



class MenteeController extends Controller
{
    public function index()
    {
        $menteeId = Auth::id();
        
        // 1. Ambil data paket aktif & sesi yang tersedia
        $activePackages = UserPackage::with(['package', 'mentor.user']) 
                                    ->where('user_id', $menteeId)
                                    ->where('status', 'active')
                                    ->where('remaining_quota', '>', 0) 
                                    ->get();

        $assignedMentorIds = $activePackages->pluck('mentor_id')->filter()->unique()->toArray();
        
        $availableSessions = [];
        if (!empty($assignedMentorIds)) {
            $availableSessions = LearningSession::with('mentor.user')
                                                ->whereIn('mentor_id', $assignedMentorIds)
                                                ->where('status', 'scheduled') 
                                                ->where('start_time', '>', now()) 
                                                ->orderBy('start_time', 'asc')
                                                ->get();
        }
        
        $pendingAssignmentPackages = UserPackage::with('package') 
                                                ->where('user_id', $menteeId)
                                                ->where('status', 'pending_assignment')
                                                ->get();

        // 2. [BARU] Ambil Daftar Paket untuk "Pricing Plan"
        $packages = Package::orderBy('price', 'asc')->get();

        // 3. Kirim semua ke View
        return view('mentee.dashboard.index', compact(
            'activePackages', 
            'availableSessions', 
            'pendingAssignmentPackages',
            'packages' // <--- Kirim variable ini
        ));
    }

    public function packages()
    {
        // Ambil semua paket, urutkan dari yang termurah
        $packages = Package::orderBy('price', 'asc')->get();

        return view('mentee.packages.index', compact('packages'));
    }
}

