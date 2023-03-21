<?php

use Core\Router;

use App\Controllers\{
    AboutController,
    AuthController,
    ContactController,
    HomeController,
    NotesController
};

Router::GET("/", [HomeController::class])->name("index");
Router::GET("home", [HomeController::class])->name("home");
Router::GET("about", [AboutController::class])->name("about");
Router::GET("contact", [ContactController::class])->name("contact");

/*** AUTH ***/

Router::GET("signup", [AuthController::class, "signupForm"])->middleware("guest")->name("signup.form");
Router::POST("signup", [AuthController::class, "signup"])->middleware("guest")->name("signup");
Router::GET("signin", [AuthController::class, "signinForm"])->middleware("guest")->name("signin.form");
Router::POST("signin", [AuthController::class, "signin"])->middleware("guest")->name("signin");
Router::POST("signout", [AuthController::class, "signout"])->middleware("auth")->name("signout");

/*** NOTES ***/

Router::GET("notes", [NotesController::class, "index"])->middleware("auth")->name("notes.index");
Router::GET("notes/create", [NotesController::class, "create"])->middleware("auth")->name("notes.create");
Router::GET("notes/{id}", [NotesController::class, "show"])->middleware("auth")->name("notes.show");
Router::GET("notes/{id}/edit", [NotesController::class, "edit"])->middleware("auth")->name("notes.edit");
Router::POST("notes", [NotesController::class, "store"])->middleware("auth")->name("notes.store");
Router::PUT("notes/{id}", [NotesController::class, "update"])->middleware("auth")->name("notes.update");
Router::DELETE("notes/{id}", [NotesController::class, "destroy"])->middleware("auth")->name("notes.destroy");
