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
Route::apiResource('v1/state_donations', 'Api\V1\StateDonationController');
Route::apiResource('v1/state_products', 'Api\V1\StateProductController');
Route::apiResource('v1/products', 'Api\V1\ProductController');
Route::post('v1/getProductsByUser', 'Api\V1\ProductController@getProductsByUser');

Route::post('login', 'Api\LoginController@login');
Route::post('registerNewUser', 'Api\RegisterController@registerNewUser');
Route::get('register/verify/{email}', 'Api\RegisterController@verifyEmail');
Route::post('validateIfExistEmail', 'Api\RegisterController@validateIfExistEmail');
Route::post('verifyCodeForgetPassword', 'Api\RegisterController@verifyCodeForgetPassword');
Route::post('changePassword', 'Api\RegisterController@changePassword');
Route::post('v1/getDataUserLogged', 'Api\V1\UserController@getDataUserLogged');

