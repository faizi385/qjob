<?php

namespace App\Traits;

use App\Jobs\AddContactJob;

trait DispatchContactJob
{
    public function dispatchContactJob(array $data)
    {
        AddContactJob::dispatch($data);
    }
}
