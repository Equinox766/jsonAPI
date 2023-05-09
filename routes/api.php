<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;




Route::apiResource('articles', ArticleController::class ); //Todos los metodos de rutas para Articles

Route::apiResource('categories',CategoryController::class ) //Todos los metodos de rutas para Categories
    ->only('index', 'show'); //Se define que solo index y show estan disponibles
