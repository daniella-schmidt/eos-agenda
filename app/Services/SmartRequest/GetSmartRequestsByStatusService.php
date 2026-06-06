<?php

namespace App\Services\SmartRequest;

use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use Illuminate\Database\Eloquent\Collection;

class GetSmartRequestsByStatusService
{
    /**
     * @return Collection<int, SmartRequest>
     */
    public function handle(string $userId, SmartRequestStatus $status): Collection
    {
        return SmartRequest::query()
            ->where('userId', $userId)
            ->where('status', $status)
            ->latest('createdAt')
            ->get();
    }
}
