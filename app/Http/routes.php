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
$app->get('/regions/ping/stat', 'PingController@ping');
$app->get('/trial', 'TrialController@check');

// Invites.
$app->get('/invites/count/pending', 'InviteController@pendingCount');
$app->get('/invites/pending', 'InviteController@view');
$app->put('/invites/accept/{id}', 'InviteController@accept');
$app->put('/invites/reject/{id}', 'InviteController@reject');
$app->post('/invites/{id}', 'InviteController@invite');

// Worlds
$app->get('/worlds/{id}/join', 'JoinController@join');
$app->get('/worlds/{id}', 'WorldController@view');
$app->get('/worlds', 'WorldController@viewall');
$app->get('/activities/liveplayerlist', 'LiveActivityController@playerlist');
$app->delete('/invites/{id}', 'WorldController@leave');
