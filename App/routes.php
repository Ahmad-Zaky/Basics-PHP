<?php

use Core\Router;

Router::GET("/", "home")->name("index");
Router::GET("home", "home")->name("home");
Router::GET("about", "about")->name("about");
Router::GET("contact", "contact")->name("contact");

/*** AUTH ***/

Router::GET("signup", "auth.signup_form")->only("guest")->name("signup.form");
Router::POST("signup", "auth.signup")->only("guest")->name("signup");
Router::GET("signin", "auth.signin_form")->only("guest")->name("signin.form");
Router::POST("signin", "auth.signin")->only("guest")->name("signin");
Router::POST("signout", "auth.signout")->only("auth")->name("signout");

/*** NOTES ***/

Router::GET("notes", "notes.index")->only("auth")->name("notes.index");
Router::GET("note", "notes.show")->only("auth")->name("notes.show");
Router::GET("notes/create", "notes.create")->only("auth")->name("notes.create");
Router::GET("notes/edit", "notes.edit")->only("auth")->name("notes.edit");
Router::POST("notes", "notes.store")->only("auth")->name("notes.store");
Router::PUT("notes", "notes.update")->only("auth")->name("notes.update");
Router::DELETE("notes", "notes.destroy")->only("auth")->name("notes.destroy");
