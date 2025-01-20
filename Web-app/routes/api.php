<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(function () {
    Route::resource('posts', PostsController::class);
    Route::get('/userPost/{id}', [PostsController::class, 'userPost']);
    Route::delete('/posts/{id}', [PostsController::class, 'destroy']);
    Route::get('/postComments/{id}', [PostsController::class, 'getPostComments']);
    Route::post('/addComment', [PostsController::class, 'addCommentToPost']);
    Route::post('/edit-comment/{id}', [PostsController::class, 'editComment']);
    Route::delete('/delete-comment/{id}', [PostsController::class, 'deleteComment']);
    Route::post('/editPost/{id}', [PostsController::class, 'editPost']);
    Route::get('/email-verification/{id}/{action}', [UserController::class, 'emailVerification']);
    Route::get('/postSearch', [PostsController::class, 'postSearch']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
