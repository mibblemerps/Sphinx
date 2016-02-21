<?php

namespace App\Http\Controllers;

use App\Realms\Invite;

/**
 * Class InviteController
 * @package App\Http\Controllers
 */
class InviteController
{
    protected function generateInviteJson($invite) {

        // Formulate JSON response.
        $json = [
            'invitationId' => $invite->invitationId,
            'worldName' => $invite->worldName,
            'worldOwnerName' => $invite->worldOwnerName,
            'worldOwnerUuid' => $invite->worldOwnerUuid,
            'date' => $invite->invitedate,
        ];

        return $json;
    }

    /**
     * Get pending invite count.
     *
     * @return string
     */
    public function pendingCount()
    {
        return '1';
    }


    public function view()
    {
        $invite = new Invite();
        $invite->invitationId = 1;
        $invite->worldName = 'potatocraft';
        $invite->worldOwnerName = 'mitchfizz05';
        $invite->worldOwnerUuid = 'b6284cef69f440d2873054053b1a925d';
        $invite->invitedate = '1455922800000';

        return [
            'invites' => [
                $this->generateInviteJson($invite)
            ]
        ];
    }
}
