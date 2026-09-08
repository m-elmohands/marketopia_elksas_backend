<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexContentRequest;
use App\Http\Resources\Api\V1\ArticleResource;
use App\Models\Content\Article;
use App\Services\Content\PublicContentService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(private readonly PublicContentService $content) {}

    public function index(IndexContentRequest $request): JsonResponse
    {
        app()->setLocale($request->validated('locale', 'ar'));
        $paginator = $this->content->articles($request->validated());

        return response()->json(['data' => ArticleResource::collection($paginator->items())->resolve(), 'message' => null, 'errors' => null, 'meta' => ['current_page' => $paginator->currentPage(), 'last_page' => $paginator->lastPage(), 'per_page' => $paginator->perPage(), 'total' => $paginator->total()]]);
    }

    public function show(IndexContentRequest $request, Article $article): JsonResponse
    {
        abort_unless($article->published_at?->isPast(), 404);
        app()->setLocale($request->validated('locale', 'ar'));

        return response()->json(['data' => (new ArticleResource($article->load(['translations', 'category.translations', 'media'])))->resolve(), 'message' => null, 'errors' => null]);
    }
}
