<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \app\Http\Controller\Api\TagController;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'namespace' => 'Api',
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('signup', 'ApiAuthController@signup');
    Route::post('login', 'ApiAuthController@login');
    Route::post('logout', 'ApiAuthController@logout');
    Route::post('refresh', 'ApiAuthController@refresh');
    Route::post('me', 'ApiAuthController@me');
});

Route::apiResource('/categories', 'Api\CategoryController');
Route::apiResource('/tags', 'Api\TagController'  );
