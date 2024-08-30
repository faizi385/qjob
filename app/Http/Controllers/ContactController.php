<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Rules\ContactImportValidation;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = prepareContactData('Faizan', 'faizannovatore@example.com', '1234567890');

        $this->dispatchContactJob($data);

        return response()->json(['message' => 'Contact job dispatched successfully!']);
    }



    
    public function import(Request $request)
    {
       
        $request->validate([
            'contacts_file' => 'required|file|mimes:xlsx,xls',
        ]);
    
        $import = new ContactsImport();
    
        try {
            DB::beginTransaction(); 
    
            Excel::import($import, $request->file('contacts_file'));
    
            $invalidRows = [];
            $validRows = [];
    
      
            foreach ($import->getRows() as $row) {
                $rowArray = $row->toArray(); 
    
             
                $validator = $import->validateRow($rowArray);
    
                if ($validator->fails()) {
                    $invalidRows[] = ['row' => $rowArray, 'errors' => $validator->errors()->all()];
                } else {
                    $validRows[] = $rowArray;
                }
            }
    

            if ($invalidRows) {
                DB::rollback(); 
                return response()->json(['message' => 'Contacts imported with some errors.', 'errors' => $invalidRows], 400);
            }
    
            Contact::insert(array_map(fn($row) => [
                'name'  => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
            ], $validRows));
    
            DB::commit(); 
    
            return response()->json(['message' => 'Contacts imported successfully!']);
        } catch (\Throwable $e) { 
            DB::rollback();
            Log::error('Import failed: ' . $e->getMessage());
    
      
            return response()->json(['error' => 'Failed to import contacts: ' . $e->getMessage()], 400);
        }
    }
    

    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.xlsx');
    }
}
