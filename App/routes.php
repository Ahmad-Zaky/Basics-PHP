<?php

use Core\Router;

Router::GET("/", "home");
Router::GET("/about", "about");
Router::GET("/contact", "contact");

/*** NOTES ***/

Router::GET("/notes", "notes.index");
Router::GET("/note", "notes.show");
Router::GET("/notes/create", "notes.create");
Router::POST("/notes", "notes.store");
Router::DELETE("/notes", "notes.destroy");