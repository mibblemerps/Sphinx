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
$app->get('/mco/available', 'AvailableController@available');
$app->get('/mco/client/compatible', 'CompatibleController@compatible');
$app->get('/mco/stageAvailable', 'AvailableController@stagingAvailable');
$app->post('/regions/ping/stat', 'PingController@ping');
$app->get('/trial', 'TrialController@check');

// Invites.
$app->get('/invites/count/pending', 'InviteController@pendingCount');
$app->get('/invites/pending', 'InviteController@view');
$app->put('/invites/accept/{id}', 'InviteController@accept');
$app->put('/invites/reject/{id}', 'InviteController@reject');
$app->post('/invites/{id}', 'InviteController@invite');
$app->delete('/invites/{id}/invite/{player}', 'WorldController@kick');

// Worlds
$app->get('/worlds/{id}/join', 'JoinController@join');
$app->put('/worlds/{id}/close', 'WorldController@close');
$app->put('/worlds/{id}/open', 'WorldController@open');
$app->get('/worlds/{id}', 'WorldController@view');
$app->get('/worlds', 'WorldController@viewall');
$app->get('/activities/liveplayerlist', 'LiveActivityController@playerlist');
$app->delete('/invites/{id}', 'WorldController@leave');

$app->post('/ops/{id}/{player}', 'OpController@op');
$app->delete('/ops/{id}/{player}', 'OpController@deop');

// Sphinx API
$app->group(['namespace' => 'App\Http\Controllers\NodeApi', 'prefix' => '/sphinx/api'], function () use ($app) {
    $app->get('/ping', 'PingController@ping');
    $app->get('/request-manifest', 'ManifestController@request');
});
