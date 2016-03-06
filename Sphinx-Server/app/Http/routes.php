<?php

use Illuminate\Support\Facades\Route;

/*
 * Sphinx Routes
*/

Route::get('/', function () {
    return redirect('https://github.com/mitchfizz05/Sphinx');
});

if (env('APP_DEBUG') && !App\Facades\MinecraftAuth::check()) {
    App\Facades\MinecraftAuth::set(new App\Realms\Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05'));
}

// Availability.
Route::get('/mco/available', 'AvailabilityController@available');
Route::get('/mco/client/compatible', 'AvailabilityController@compatible');
Route::get('/mco/stageAvailable', 'AvailabilityController@stagingAvailable');
Route::post('/regions/ping/stat', 'AvailabilityController@regionPing');
Route::get('/trial', 'AvailabilityController@trialAvailable');

// Invites.
Route::get('/invites/count/pending', 'InviteController@pendingCount');
Route::get('/invites/pending', 'InviteController@view');
Route::put('/invites/accept/{id}', 'InviteController@accept');
Route::put('/invites/reject/{id}', 'InviteController@reject');
Route::post('/invites/{id}', 'InviteController@invite');
Route::delete('/invites/{id}/invite/{player}', 'WorldController@kick');

// Realms
Route::get('/worlds', 'WorldController@listing');
Route::get('/activities/liveplayerlist', 'LiveActivityController@playerlist');
Route::delete('/invites/{id}', 'WorldController@leave');

// Realm Management
Route::get('/worlds/{id}/join', 'WorldController@join');
Route::put('/worlds/{id}/close', 'WorldController@close');
Route::put('/worlds/{id}/open', 'WorldController@open');
Route::get('/worlds/{id}', 'WorldController@view');
Route::post('/worlds/{id}', 'WorldController@UpdateServerInfo');
Route::post('/worlds/{id}/initialize', 'WorldController@InitServer');
Route::post('/ops/{id}/{player}', 'OpController@op');
Route::delete('/ops/{id}/{player}', 'OpController@deop');
Route::get('/subscriptions/{id}', 'SubscriptionController@view');

// Sphinx API
Route::group(['namespace' => 'NodeApi', 'prefix' => '/sphinx/api'], function () {
    Route::get('/ping', 'PingController@ping');
    Route::get('/request-manifest', 'ManifestController@request');
});

// Sphinx Dashboard
Route::group(['namespace' => 'Dashboard', 'prefix' => '/sphinx/dashboard', 'middleware' => 'web'], function () {
    // Login routes.
    Route::get('/login', 'AuthController@loginForm');
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout')->middleware('csrf.get');

    // Restricted routes that require the user to be logged in.
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', 'DashboardController@dashboard');
        Route::get('/realms', 'RealmsController@listing');

        Route::get('/ajax/stats', 'DashboardController@statsApi');
        Route::post('/ajax/create_realm', 'RealmsController@create');
    });
});
