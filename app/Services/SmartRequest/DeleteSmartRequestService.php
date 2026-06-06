<?php

namespace App\Services\SmartRequest;

use App\Models\SmartRequest;

class DeleteSmartRequestService
{
    public function handle(SmartRequest $smartRequest): void
    {
        $smartRequest->delete();
    }
}
