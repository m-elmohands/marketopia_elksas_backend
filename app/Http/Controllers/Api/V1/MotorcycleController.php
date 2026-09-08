<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexContentRequest;
use App\Http\Resources\Api\V1\MotorcycleResource;
use App\Models\Content\Motorcycle;
use App\Services\Content\PublicContentService;
use Illuminate\Http\JsonResponse;

class MotorcycleController extends Controller
{
    public function __construct(private readonly PublicContentService $content) {}

    public function index(IndexContentRequest $request): JsonResponse
    {
        app()->setLocale($request->validated('locale', 'ar'));
        $paginator = $this->content->motorcycles($request->validated());

        return response()->json(['data' => MotorcycleResource::collection($paginator->items())->resolve(), 'message' => null, 'errors' => null, 'meta' => ['current_page' => $paginator->currentPage(), 'last_page' => $paginator->lastPage(), 'per_page' => $paginator->perPage(), 'total' => $paginator->total()]]);
    }

    public function show(IndexContentRequest $request, Motorcycle $motorcycle): JsonResponse
    {
        abort_unless($motorcycle->published_at?->isPast(), 404);
        app()->setLocale($request->validated('locale', 'ar'));

        return response()->json(['data' => (new MotorcycleResource($motorcycle->load(['translations', 'category.translations', 'media'])))->resolve(), 'message' => null, 'errors' => null]);
    }
}
