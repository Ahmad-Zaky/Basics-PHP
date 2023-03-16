<?php

use Core\Router;

Router::GET("/", "home");
Router::GET("/home", "home");
Router::GET("/about", "about");
Router::GET("/contact", "contact");

/*** AUTH ***/

Router::GET("/signup", "auth.signup_form")->only("guest");
Router::POST("/signup", "auth.signup")->only("guest");
Router::GET("/signin", "auth.signin_form")->only("guest");
Router::POST("/signin", "auth.signin")->only("guest");
Router::POST("/signout", "auth.signout")->only("auth");

/*** NOTES ***/

Router::GET("/notes", "notes.index")->only("auth");
Router::GET("/note", "notes.show")->only("auth");
Router::GET("/notes/create", "notes.create")->only("auth");
Router::GET("/notes/edit", "notes.edit")->only("auth");
Router::POST("/notes", "notes.store")->only("auth");
Router::PUT("/notes", "notes.update")->only("auth");
Router::DELETE("/notes", "notes.destroy")->only("auth");
