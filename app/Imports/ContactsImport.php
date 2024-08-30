<?php
namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;

class ContactsImport implements ToModel, WithHeadingRow
{
    protected $requiredFields = ['name', 'email', 'phone'];
    protected $missingRows = [];

    public function model(array $row)
    {
        $missingFields = array_diff($this->requiredFields, array_keys($row));

        if (empty($missingFields)) {
            return new Contact([
                'name'  => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
            ]);
        }

        // Store information about the row with missing fields
        $this->missingRows[] = $row;
        return null; // Skip this row
    }

    public function __destruct()
    {
        // Store the missing rows in the session when the import is finished
        if (!empty($this->missingRows)) {
            Session::flash('missing_rows', $this->missingRows);
        }
    }
}
