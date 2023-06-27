<?php

use Core\{
    AuthenticationManager,
    ConfigManager,
    Controller,
    CookieManager,
    DatabaseManager,
    EventManager,
    MiddlewareManager,
    MigrationManager,
    Model,
    RequestManager,
    ResponseManager,
    RouterManage,
    SessionManager,
    TranslationManager,
    ValidatorManager,
    ViewManager
};
use Core\Contracts\{
    Auth,
    Config,
    Cookie,
    DB,
    Event,
    Middleware,
    Migration,
    Request,
    Response,
    Session,
    Validator,
    View
};

return [
    "name" => env("APP_NAME", "MVC"),
    
    "environtment" => env("APP_ENV", "local"),
    
    "debug" => env("APP_DEBUG", true), 

    "url" => env("APP_URL", "http://localhost:5000"),

    'authenticatable' => \App\Models\User::class,

    'authenticatable_col' => 'email',
    
    'locals' => ['ar', 'en'],

    'local' => 'en',

    'local_fallback' => 'en',

    'facades' => [
        'router' => fn() => new RouterManage,
        'trans' => fn() => new TranslationManager(new ConfigManager),
    ],

    'contracts' => [
        Config::class => fn() => new ConfigManager,
        DB::class => fn() => DatabaseManager::getInstance((new ConfigManager)->get("database.connection")),
        Session::class => fn() => new SessionManager,
        Cookie::class => fn() => new CookieManager,
        Auth::class => fn() => new AuthenticationManager(new ConfigManager, new SessionManager),
        Request::class => fn() => new RequestManager(new SessionManager),
        Validator::class => fn() => new ValidatorManager,
        Middleware::class => fn() => new MiddlewareManager,
        Controller::class => fn() => new Controller,
        Model::class => fn() => new Model,
        View::class => fn() => new ViewManager,
        Response::class => fn() => new ResponseManager,
        Migration::class => fn() => new MigrationManager(DatabaseManager::getInstance((new ConfigManager)->get("database.connection"))),
        Event::class => fn() => new EventManager,
    ]
];
