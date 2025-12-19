<?php


namespace App\Domains\Notification\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'reference_number' => $this->reference_number,
            'type' => $this->type,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'read' => (bool)$this->read,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
