<?php
namespace App\Jobs;

use App\Imports\ContactsImport;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $import = new ContactsImport();
        $validRows = [];
        $invalidRows = [];

        try {
            Log::info('Processing file: ' . $this->filePath);

            // Import the file
            Excel::import($import, $this->filePath);

            Log::info('Rows imported: ' . json_encode($import->getRows()));

            // Validate and categorize rows
            foreach ($import->getRows() as $row) {
                $rowArray = $row->toArray();

                // Logging each row
                Log::info('Processing row: ' . json_encode($rowArray));

                if ($import->validateRow($rowArray)) {
                    $validRows[] = $rowArray;
                } else {
                    $invalidRows[] = ['row' => $rowArray, 'errors' => ['Validation failed']];
                }
            }

        
            if ($invalidRows) {
                Log::error('Invalid rows: ' . json_encode($invalidRows));
            }

   
            if (!empty($validRows)) {
                Contact::insert(array_map(fn($row) => [
                    'name'  => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                ], $validRows));

                Log::info('Contacts inserted successfully.');
            } else {
                Log::info('No valid rows to insert.');
            }
        } catch (\Throwable $e) {
            Log::error('Import failed: ' . $e->getMessage());
        }
    }
}
