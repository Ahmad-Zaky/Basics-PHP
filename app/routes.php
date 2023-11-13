<?php

use Core\Facades\Route;

use App\Controllers\{
    AboutController,
    AuthController,
    ContactController,
    HomeController,
    NotesController
};
use Core\Contracts\Request;

Route::GET("/", [HomeController::class])->name("index");
Route::GET("home", [HomeController::class])->name("home");
Route::GET("about", [AboutController::class])->name("about");
Route::GET("contact", [ContactController::class])->name("contact");
Route::GET("policy", fn () => view('policy', ["heading" => __("Policy")]))->name("policy");
Route::GET("welcome/{name}", fn (Request $request, string $name) => view('welcome', [
    "heading" => __("Welcome"),
    "name" => $name,
]))->name("welcome");

Route::group(['middleware' => ['guest']], function () {
    /*** AUTH ***/
    Route::GET("signup", [AuthController::class, "signupForm"])->name("signup.form");
    Route::POST("signup", [AuthController::class, "signup"])->name("signup");
    Route::GET("signin", [AuthController::class, "signinForm"])->name("signin.form");
    Route::POST("signin", [AuthController::class, "signin"])->name("signin");
});

Route::group(['middleware' => ['auth']], function () {
    /*** AUTH ***/
    Route::POST("signout", [AuthController::class, "signout"])->name("signout");
    
    /*** NOTES ***/
    Route::resource("notes", [NotesController::class]);
});
