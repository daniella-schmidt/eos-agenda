<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmartRequestResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'rawText' => $this->rawText,
            'intent' => $this->intent,
            'extractedTitle' => $this->extractedTitle,
            'extractedDescription' => $this->extractedDescription,
            'extractedStartAt' => $this->extractedStartAt,
            'extractedEndAt' => $this->extractedEndAt,
            'extractedParticipants' => $this->extractedParticipants,
            'extractedData' => $this->extractedData,
            'status' => $this->status instanceof \BackedEnum
                ? $this->status->value
                : $this->status,
            'errorMessage' => $this->errorMessage,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
