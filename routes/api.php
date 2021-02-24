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
Route::apiResource('v1/roles', 'Api\V1\RoleController');
Route::apiResource('v1/departments', 'Api\V1\DepartmentController');
Route::apiResource('v1/cities', 'Api\V1\CityController');
Route::apiResource('v1/localities', 'Api\V1\LocalityController');

//Route::post('api-token-auth', );
