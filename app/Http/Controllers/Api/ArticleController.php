<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function show ($article): JsonResource
    {
        $article = Article::where('slug', $article)
            ->allowedIncludes(['category'])
            ->sparseFieldset()
            ->firstOrFail();
        return ArticleResource::make($article);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index (Request $request): AnonymousResourceCollection
    {

        $articles = Article::query()
            ->allowedIncludes(['category'])
            ->allowedFilters(['title', 'content', 'month', 'year', 'categories'])
            ->allowedSorts(['title', 'content'])
            ->sparseFieldset()
            ->jsonPaginate();

        return ArticleResource::collection($articles);
    }

    public function store (SaveArticleRequest $request): ArticleResource
    {

        $article = Article::create($request->validated());

        return ArticleResource::make($article);
    }

    public function update (SaveArticleRequest $request, Article $article): ArticleResource
    {

        $article->update($request->validated());

        return ArticleResource::make($article);
    }

    public function destroy (Article $article): Response
    {
        $article->delete();

        return response()->noContent();
    }

}
