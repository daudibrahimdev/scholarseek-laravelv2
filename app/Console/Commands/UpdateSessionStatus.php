<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LearningSession;

class UpdateSessionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-session-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $this->info("Waktu Sekarang (Aplikasi): " . $now->format('Y-m-d H:i:s'));

        // 1. Scheduled -> Ongoing
        $ongoingCount = LearningSession::where('status', 'scheduled')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => 'ongoing']);

        if($ongoingCount > 0) $this->info("$ongoingCount sesi berubah jadi Ongoing.");

        // 2. Scheduled/Ongoing -> Completed
        $sessionsToComplete = LearningSession::whereIn('status', ['scheduled', 'ongoing'])
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($sessionsToComplete as $session) {
            $session->update(['status' => 'completed']);
            $this->info("Sesi ID {$session->id} ({$session->title}) telah selesai.");

            // OTOMASI: Jika 1on1, cek kuota paket mentee
            if ($session->type == '1on1') {
                $participant = $session->participants()->first();
                if ($participant && $participant->user_package_id) {
                    $package = \App\Models\UserPackage::find($participant->user_package_id);
                    // Jika kuota sudah 0, ubah status paket jadi used_up agar bisa beli lagi
                    if ($package && $package->remaining_quota <= 0) {
                        $package->update(['status' => 'used_up']);
                        $this->info("Paket Mentee {$package->mentee->name} diubah menjadi used_up.");
                    }
                }
            }
        }

        $this->info('Pengecekan selesai.');
    }
}
