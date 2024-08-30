<?php
namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToCollection, WithHeadingRow
{
    protected $rows = [];

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function validateRow(array $row)
    {
        $validator = Validator::make($row, [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|numeric',
        ]);

        return $validator;
    }
}
