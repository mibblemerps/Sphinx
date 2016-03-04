<?php

/*
 * Sphinx Routes
*/

$app->get('/', function () {
    return redirect('https://github.com/mitchfizz05/Sphinx');
});

if (env('APP_DEBUG') && !App\Facades\MinecraftAuth::check()) {
    App\Facades\MinecraftAuth::set(new App\Realms\Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05'));
}

// Availability.
$app->get('/mco/available', 'AvailabilityController@available');
$app->get('/mco/client/compatible', 'AvailabilityController@compatible');
$app->get('/mco/stageAvailable', 'AvailableController@stagingAvailable');
$app->post('/regions/ping/stat', 'AvailabilityController@regionPing');
$app->get('/trial', 'AvailabilityController@trialAvailable');

// Invites.
$app->get('/invites/count/pending', 'InviteController@pendingCount');
$app->get('/invites/pending', 'InviteController@view');
$app->put('/invites/accept/{id}', 'InviteController@accept');
$app->put('/invites/reject/{id}', 'InviteController@reject');
$app->post('/invites/{id}', 'InviteController@invite');
$app->delete('/invites/{id}/invite/{player}', 'WorldController@kick');

// Realms
$app->get('/worlds', 'WorldController@listing');
$app->get('/activities/liveplayerlist', 'LiveActivityController@playerlist');
$app->delete('/invites/{id}', 'WorldController@leave');

// Realm Management
$app->get('/worlds/{id}/join', 'WorldController@join');
$app->put('/worlds/{id}/close', 'WorldController@close');
$app->put('/worlds/{id}/open', 'WorldController@open');
$app->get('/worlds/{id}', 'WorldController@view');
$app->post('/ops/{id}/{player}', 'OpController@op');
$app->delete('/ops/{id}/{player}', 'OpController@deop');
$app->get('/subscriptions/{id}', 'SubscriptionController@view');

// Sphinx API
$app->group(['namespace' => 'App\Http\Controllers\NodeApi', 'prefix' => '/sphinx/api'], function () use ($app) {
    $app->get('/ping', 'PingController@ping');
    $app->get('/request-manifest', 'ManifestController@request');
});
