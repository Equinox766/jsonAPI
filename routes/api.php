<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;




Route::apiResource('articles', ArticleController::class ); //Todos los metodos de rutas para Articles

Route::apiResource('categories',CategoryController::class ) //Todos los metodos de rutas para Categories
    ->only('index', 'show'); //Se define que solo index y show estan disponibles

Route::apiResource('authors',AuthorController::class ) //Todos los metodos de rutas para Users
    ->only('index', 'show'); //Se define que solo index y show estan disponibles

Route::get('articles/{article}/relationships/category', fn() => 'TODO')
    ->name('articles.relationships.category');

Route::get('articles/{article}/category', fn() => 'TODO')
    ->name('articles.category');

Route::get('articles/{article}/relationships/author', fn() => 'TODO')
    ->name('articles.relationships.author');

Route::get('articles/{article}/author', fn() => 'TODO')
    ->name('articles.author');
