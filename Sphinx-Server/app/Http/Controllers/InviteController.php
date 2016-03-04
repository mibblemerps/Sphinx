<?php

namespace App\Http\Controllers;

use App\Facades\MinecraftAuth;
use App\Realms\Invite;
use App\Realms\Player;
use App\Realms\Server;
use Illuminate\Http\Request;

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
            if ($invite->accepted) {
                continue; // Invite already accepted, don't display.
            }

            $invitesJson[] = $this->generateInviteJson($invite);
        }

        // Return response.
        return [
            'invites' => $invitesJson
        ];
    }

    /**
     * Reject an invitation.
     *
     * @param int $id Invite ID
     * @return string
     */
    public function reject($id)
    {
        $invite = Invite::find($id);

        // Check the invite belongs to this user.
        if ($invite->to->uuid != Player::current()->uuid) {
            // This invite does not belong to the current user!
            abort(403); // 403 Forbidden.
        }

        // Check that the invite hasn't been accepted yet.
        if ($invite->accepted) {
            // Already accepted.
            abort(400); // 400 Bad Request
        }

        // Discard invite.
        $invite->delete();

        return '';
    }

    /**
     * Accept an invitation.
     *
     * @param int $id Invite ID
     * @return string
     */
    public function accept($id)
    {
        $invite = Invite::find($id);

        // Check the invite belongs to this user.
        if ($invite->to->uuid != Player::current()->uuid) {
            // This invite does not belong to the current user!
            abort(403); // 403 Forbidden.
        }

        // Check that the invite hasn't been accepted yet.
        if ($invite->accepted) {
            // Already accepted.
            abort(400); // 400 Bad Request
        }

        $server = $invite->server;

        // Add current player to servers invited players list.
        // Couldn't directly append new player to invited players array due to PHP bug. https://bugs.php.net/bug.php?id=41641
        $invited = $server->invited_players;
        $invited[] = Player::current();
        $server->invited_players = $invited;
        $server->save();

        // Mark invite as accepted.
        $invite->accepted = true;
        $invite->save();

        return '';
    }

    /**
     * Send an invitation to another player.
     *
     * @param Request $request
     * @param int $serverId Server ID
     * @return mixed
     * @throws \Exception
     */
    public function invite(Request $request, $serverId)
    {
        // Check if player has rights to invite people to Realm.
        $server = Server::findOrFail($serverId);
        if ($server->owner->uuid != Player::current()->uuid) {
            abort(403); // 403 Forbidden.
        }

        $player = new Player(null, $request->input('name'));
        $player->lookupFromApi(); // fetch uuid from API.

        // Create invitation.
        Invite::create([
            'realm_id' => $serverId,
            'to' => $player
        ]);

        // Return updated server json.
        return WorldController::generateServerJSON($server);
    }
}
