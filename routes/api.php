<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    /*Route::get('admin/profile', function () {
        //
    })->withoutMiddleware([CheckAge::class]);*/
});

Route::apiResource('v1/categories', 'Api\V1\CategoryController');
Route::apiResource('v1/type_documents','Api\V1\TypeDocumentController');
//Route::post('api-token-auth', );
