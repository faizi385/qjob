<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Imports\ContactsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ImportContactsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        $import = new ContactsImport();
        $invalidRows = [];
        $validRows = [];

        try {
            DB::beginTransaction();

            Excel::import($import, $this->file);

            foreach ($import->getRows() as $row) {
                $rowArray = $row->toArray();

                $validator = Validator::make($rowArray, [
                    'name'  => 'required|string|max:255',
                    'email' => 'required|email',
                    'phone' => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    $invalidRows[] = ['row' => $rowArray, 'errors' => $validator->errors()->all()];
                } else {
                    $validRows[] = $rowArray;
                }
            }

            if ($invalidRows) {
                DB::rollback();
                Log::info('Contacts imported with some errors.', ['errors' => $invalidRows]);
                return;
            }

            Contact::insert(array_map(fn($row) => [
                'name'  => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
            ], $validRows));

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Import failed: ' . $e->getMessage());
        }
    }
}
