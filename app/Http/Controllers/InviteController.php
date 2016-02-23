<?php

namespace App\Http\Controllers;

use App\Realms\Invite;
use Carbon\Carbon;

/**
 * Class InviteController
 * @package App\Http\Controllers
 */
class InviteController
{
    protected function generateInviteJson($invite)
    {
        // Formulate JSON response.
        $json = [
            'invitationId' => $invite->id,
            'worldName' => $invite->worldName,
            'worldOwnerName' => $invite->worldOwnerName,
            'worldOwnerUuid' => $invite->worldOwnerUuid,
            'date' => strtotime($invite->created_at) . '000',
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
        $invite->id = 1;
        $invite->worldName = 'Potatocraft';
        $invite->worldOwnerName = 'mitchfizz05';
        $invite->worldOwnerUuid = 'b6284cef69f440d2873054053b1a925d';
        $invite->created_at = 1455922800;

        return [
            'invites' => [
                $this->generateInviteJson($invite)
            ]
        ];
    }
}
