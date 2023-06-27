<?php

use Core\Facades\Route;

use App\Controllers\{
    AboutController,
    AuthController,
    ContactController,
    HomeController,
    NotesController
};

Route::GET("/", [HomeController::class])->name("index");
Route::GET("home", [HomeController::class])->name("home");
Route::GET("about", [AboutController::class])->name("about");
Route::GET("contact", [ContactController::class])->name("contact");

/*** AUTH ***/

Route::GET("signup", [AuthController::class, "signupForm"])->middleware("guest")->name("signup.form");
Route::POST("signup", [AuthController::class, "signup"])->middleware("guest")->name("signup");
Route::GET("signin", [AuthController::class, "signinForm"])->middleware("guest")->name("signin.form");
Route::POST("signin", [AuthController::class, "signin"])->middleware("guest")->name("signin");
Route::POST("signout", [AuthController::class, "signout"])->middleware("auth")->name("signout");

/*** NOTES ***/

Route::GET("notes", [NotesController::class, "index"])->middleware("auth")->name("notes.index");
Route::GET("notes/create", [NotesController::class, "create"])->middleware("auth")->name("notes.create");
Route::GET("notes/{id}", [NotesController::class, "show"])->middleware("auth")->name("notes.show");
Route::GET("notes/{id}/edit", [NotesController::class, "edit"])->middleware("auth")->name("notes.edit");
Route::POST("notes", [NotesController::class, "store"])->middleware("auth")->name("notes.store");
Route::PUT("notes/{id}", [NotesController::class, "update"])->middleware("auth")->name("notes.update");
Route::DELETE("notes/{id}", [NotesController::class, "destroy"])->middleware("auth")->name("notes.destroy");
