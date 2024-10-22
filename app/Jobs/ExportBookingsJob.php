<?php

namespace App\Jobs;

use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class ExportBookingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filters;

    /**
     * Create a new job instance.
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $filename = 'bookings_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

            $path = Excel::store(new BookingsExport($this->filters), 'public/' . $filename);

            if ($path) {
                Log::info("Booking export saved successfully at: " . storage_path('app/public/' . $filename));
            } else {
                Log::error("Failed to save booking export.");
            }
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
        }
    }
}
