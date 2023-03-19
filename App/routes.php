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

Router::GET("signup", [AuthController::class, "signupForm"])->only("guest")->name("signup.form");
Router::POST("signup", [AuthController::class, "signup"])->only("guest")->name("signup");
Router::GET("signin", [AuthController::class, "signinForm"])->only("guest")->name("signin.form");
Router::POST("signin", [AuthController::class, "signin"])->only("guest")->name("signin");
Router::POST("signout", [AuthController::class, "signout"])->only("auth")->name("signout");

/*** NOTES ***/

Router::GET("notes", [NotesController::class, "index"])->only("auth")->name("notes.index");
Router::GET("note", [NotesController::class, "show"])->only("auth")->name("notes.show");
Router::GET("notes/create", [NotesController::class, "create"])->only("auth")->name("notes.create");
Router::GET("notes/edit", [NotesController::class, "edit"])->only("auth")->name("notes.edit");
Router::POST("notes", [NotesController::class, "store"])->only("auth")->name("notes.store");
Router::PUT("notes", [NotesController::class, "update"])->only("auth")->name("notes.update");
Router::DELETE("notes", [NotesController::class, "destroy"])->only("auth")->name("notes.destroy");
