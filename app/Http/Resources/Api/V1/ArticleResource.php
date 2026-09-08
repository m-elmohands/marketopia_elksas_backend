<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'slug' => $this->slug, 'title' => $this->title, 'excerpt' => $this->excerpt,
            'body' => $this->when($request->routeIs('articles.show'), $this->body),
            'image_url' => $this->getFirstMediaUrl('cover') ?: null,
            'is_featured' => $this->is_featured, 'published_at' => $this->published_at?->toAtomString(),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
