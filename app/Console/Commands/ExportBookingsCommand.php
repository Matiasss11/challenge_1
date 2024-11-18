<?php

namespace App\Console\Commands;

use App\Jobs\ExportBookingsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExportBookingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-bookings-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command triggers the Job that generates the CSV file with the bookins, for testing, debugging or manual execution.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            ExportBookingsJob::dispatch();

            $this->info('Booking export job dispatched successfully.');
        } catch (\Exception $e) {
            Log::error('Command Export failed: ' . $e->getMessage());
        }
    }
}
