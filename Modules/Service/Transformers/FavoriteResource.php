<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service' => new ServiceResource($this->service)
        ];
    }
}
