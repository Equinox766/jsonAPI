<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    public function show($author): JsonResource
    {
        $author = User::findOrFail($author);

        return AuthorResource::make($author);

    }
    public function index(): AnonymousResourceCollection
    {
        $author = User::jsonPaginate();

        return AuthorResource::collection($author);
    }
}
