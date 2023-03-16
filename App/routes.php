<?php

use Core\Router;

Router::GET("/", "home");
Router::GET("/about", "about");
Router::GET("/contact", "contact");

/*** AUTH ***/

Router::GET("/signup", "auth.signup_form");
Router::POST("/signup", "auth.signup");
Router::GET("/signin", "auth.signin_form");
Router::POST("/signin", "auth.signin");
Router::POST("/signout", "auth.signout");

/*** NOTES ***/

Router::GET("/notes", "notes.index");
Router::GET("/note", "notes.show");
Router::GET("/notes/create", "notes.create");
Router::GET("/notes/edit", "notes.edit");
Router::POST("/notes", "notes.store");
Router::PUT("/notes", "notes.update");
Router::DELETE("/notes", "notes.destroy");
