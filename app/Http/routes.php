<?php

/**
 * Routes
 * -------------------------------------
 * Notice: put root '/' paths at bottom when route registering
 */

// auth restricted
Route::group(['middleware' => 'auth'], function() {

    // frontend
    Route::group(['namespace' => 'Frontend'], function() {

        Route::controllers([
            'debug'          => 'DebugController',
            'home'           => 'HomeController',
            'settings'       => 'SettingsController',
            'oauth'          => 'OAuthController',
            'host/{action}'  => 'HostController',
            'link/{link_id}' => 'LinkController',

            // datatables
            'users'   => 'UsersController',
            'links'   => 'LinksController',
            'origins' => 'OriginsController',
        ]);
    });

    // nlp
    Route::group(['namespace' => 'Nlp', 'prefix' => 'nlp'], function() {
        Route::controllers([
            'link-tag' => 'LinkTagController',
            'documents/{document_id?}' => 'DocumentsController',
            'train/{model_id?}' => 'TrainController',
        ]);
    });
     
});

// open (public) routes / auth
Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
    '/'        => 'Open\LandingController', 
]);
