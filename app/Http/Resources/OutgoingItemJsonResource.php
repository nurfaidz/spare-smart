<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutgoingItemJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'spare_part' => $this->sparePart->name,
            'code' => $this->sparePart->code,
            'stock_current' => $this->sparePart->stock,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'incoming_at' => Carbon::parse($this->outgoing)->format('d-m-Y'),
            'note' => $this->note,
            'status' => $this->status,
            'note_cancellation' => $this->note_cancellation,
        ];
    }
}
