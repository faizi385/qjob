<?php

namespace App\Http\Controllers;

use App\Traits\DispatchContactJob;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use DispatchContactJob; 

    public function store(Request $request)
    {
        $data = prepareContactData('Faizan', 'faizannovatore@example.com', '1234567890');

        $this->dispatchContactJob($data);

        return response()->json(['message' => 'Contact job dispatched successfully!']);
    }
}
