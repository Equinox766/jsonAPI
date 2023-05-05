<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Http\Requests\SaveArticleRequest;

class ArticleController extends Controller
{
    public function show (Article $article)
    {
        return ArticleResource::make($article);
    }

    public function index (): ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    public function store (SaveArticleRequest $request): ArticleResource
    {

        $article = Article::create($request->validated());

        return ArticleResource::make($article);
    }

    public function update (SaveArticleRequest $request, Article $article)
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
