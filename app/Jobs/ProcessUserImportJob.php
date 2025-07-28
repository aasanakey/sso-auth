<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessUserImportJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $timeout = 120;


    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->filePath;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(private string $filePath, private int $chunkSize = 1000)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $now = Carbon::now();

        // Open local storage file for streaming
        $fullPath = Storage::path($this->filePath);
        $handle = fopen($fullPath, 'r');
        if (!$handle) {
            //TODO: Optionally: dispatch failed job event/cleanup
            return;
        }

        $header = null;
        $data = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (!$header) {
                $header = $row; // First row as header
                continue;
            }
            $record = array_combine($header, $row);

            // Add timestamp fields manually
            $record['created_at'] = $now;
            $record['updated_at'] = $now;

            //TODO: Optionally: Validate $record here

            $data[] = $record;

            if (count($data) === $this->chunkSize) {
                User::insert($data);
                $data = [];
            }
        }
        if (count($data)) {
            User::insert($data);
        }
        fclose($handle);

        // Cleanup (optional): delete the file after import
        Storage::delete($this->filePath);
    }
}
