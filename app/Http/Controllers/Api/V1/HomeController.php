<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ArticleResource;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\MotorcycleResource;
use App\Services\Content\PublicContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(private readonly PublicContentService $content) {}

    public function __invoke(Request $request): JsonResponse
    {
        $locale = $request->validate(['locale' => ['sometimes', 'in:ar,en']])['locale'] ?? 'ar';
        app()->setLocale($locale);
        $content = $this->content->home($locale);

        return response()->json(['data' => ['settings' => $content['settings'], 'categories' => CategoryResource::collection($content['categories'])->resolve(), 'motorcycles' => MotorcycleResource::collection($content['motorcycles'])->resolve(), 'articles' => ArticleResource::collection($content['articles'])->resolve()], 'message' => null, 'errors' => null]);
    }
}
