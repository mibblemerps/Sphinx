<?php

namespace App\Http\Controllers;

use App\Realms\Invite;
use App\Realms\Player;

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
            'invitationId' => intval($invite->id),
            'worldName' => $invite->world_name,
            'worldOwnerName' => $invite->world_owner->username,
            'worldOwnerUuid' => $invite->world_owner->uuid,
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
        $invite->world_name = 'Potatocraft';
        $invite->world_owner = new Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05');
        $invite->created_at = 1455922800;

        return [
            'invites' => [
                $this->generateInviteJson($invite)
            ]
        ];
    }
}
