<?php

namespace App\Http\Controllers;

use App\Facades\MinecraftAuth;
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
            'worldName' => $invite->server->name,
            'worldOwnerName' => $invite->server->owner->username,
            'worldOwnerUuid' => $invite->server->owner->uuid,
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
        return count(Player::current()->getInvites());
    }

    /**
     * View available invites.
     *
     * @return array
     */
    public function view()
    {
        $invites = Player::current()->getInvites();

        // Generate JSON structure.
        $invitesJson = [];
        foreach ($invites as $invite) {
            $invitesJson[] = $this->generateInviteJson($invite);
        }

        // Return response.
        return [
            'invites' => $invitesJson
        ];
    }
}
