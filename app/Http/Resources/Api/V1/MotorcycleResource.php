<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotorcycleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'slug' => $this->slug, 'name' => $this->name, 'manufacturer' => $this->manufacturer,
            'model_year' => $this->model_year, 'engine_cc' => $this->engine_cc, 'power_hp' => $this->power_hp,
            'price' => $this->price, 'currency' => $this->currency, 'summary' => $this->summary,
            'details' => $this->when($request->routeIs('motorcycles.show'), $this->details),
            'images' => $this->getMedia('gallery')->map(fn ($media) => ['url' => $media->getUrl(), 'name' => $media->name])->values(),
            'is_featured' => $this->is_featured, 'published_at' => $this->published_at?->toAtomString(),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
