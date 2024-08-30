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
            'contacts_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
    
        Excel::import(new ContactsImport, $request->file('contacts_file'));
    
        if (session()->has('missing_rows')) {
            return redirect()->back()->with('warning', 'Some rows were missing required fields and were not imported.');
        }
    
        return redirect()->back()->with('success', 'Contacts imported successfully.');
    }
    
    
    

    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.xlsx');
    }
}
