<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CheckInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ubah status user menjadi non-aktif jika offline > 1 bulan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Batas waktu: Hari ini dikurangi 1 bulan
        $limitDate = Carbon::now()->subMonth();

        // Cari user yang 'aktif' TAPI 'last_active_at' nya lebih lama dari batas waktu
        // ATAU user yang belum pernah login sama sekali (last_active_at null) dan dibuat > 1 bulan lalu
        $affected = User::where('status', 'aktif')
            ->where(function ($query) use ($limitDate) {
                $query->where('last_active_at', '<', $limitDate)
                    ->orWhere(function ($q) use ($limitDate) {
                        $q->whereNull('last_active_at')
                            ->where('created_at', '<', $limitDate);
                    });
            })
            ->update(['status' => 'non-aktif']);

        $this->info("Berhasil! Sebanyak {$affected} user diubah menjadi non-aktif.");
    }
}