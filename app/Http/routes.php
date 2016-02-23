<?php

/*
 * Sphinx Routes
*/

$app->get('/', function () {
    return redirect('https://github.com/mitchfizz05/Sphinx');
});

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

// Worlds
$app->get('/worlds/{id}/join', 'JoinController@join');
$app->get('/worlds', 'WorldController@viewall');
$app->get('/activities/liveplayerlist', 'LiveActivityController@playerlist');
$app->get('/worlds/{id}', 'WorldController@viewall');
$app->delete('/invites/{id}', 'WorldController@leave');
