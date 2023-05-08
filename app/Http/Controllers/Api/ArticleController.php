<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Http\Requests\SaveArticleRequest;

class ArticleController extends Controller
{
    public function show (Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function index (Request $request): ArticleCollection
    {
        //Se utiliza macro para ordenar con "allowedSorts" y para listar "jsonPginate"
        $articles = Article::allowedSorts(['title', 'content']);

        return ArticleCollection::make($articles->jsonPaginate());
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
