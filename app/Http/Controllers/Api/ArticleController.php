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

        $articles = Article::query();

        $allowedFilters = ['title', 'content', 'month', 'year'];
        //filtro
        foreach (request('filter', []) as $filter => $value) {

            abort_unless(in_array($filter, $allowedFilters), 400);

            if ($filter === 'year') {
                $articles->whereYear('created_at', $value);
            } else if ($filter === 'month') {
                $articles->whereMonth('created_at', $value);
            } else {
                $articles->where($filter, 'LIKE', '%' . $value . '%');
            }
        }

        //Se utiliza macro para ordenar con "allowedSorts" y para listar "jsonPaginate"
        $articles->allowedSorts(['title', 'content']);

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
