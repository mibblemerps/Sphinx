<?php

namespace App\SphinxNode;

use App\Realms\Player;
use App\Realms\Server;
use WebSocket\Client;

/**
 * Class for communicating with a Sphinx node.
 *
 * @package App\SphinxNode
 */
class SphinxNode
{
    /**
     * @var string Address and port to the node's websocket server.
     */
    protected $nodeAddress;

    /**
     * @var
     */
    protected $connection;

    public function __construct($nodeAddress)
    {
        $this->nodeAddress = $nodeAddress;

        // Connect to the node.
        $this->connection = new Client($nodeAddress);

        // Change timeout so long running operations don't timeout.
        $this->connection->setTimeout(30);
    }

    /**
     * Send a simple ping packet to the node and await a response.
     * Returns true of successful ping, or false if something went wrong.
     *
     * @return bool
     */
    public function ping()
    {
        try {
            // Send a simple ping packet.
            $this->connection->send(json_encode([
                'action' => 'ping',
                'time' => time()
            ]));

            $resp = json_decode($this->connection->receive());

            return ($resp->action == 'pong');
        } catch (\Exception $e) {
            // Ping failed.
            return false;
        }
    }

    /**
     * Send the servers manifest to the nodejs server.
     *
     * @param int[] $forServers Array of server IDs to include.
     * @param bool $needRestart Should the node restart the server after receiving these changes?
     * @return array
     */
    public function sendManifest($forServers = [], $needRestart = false)
    {
        // Generate json
        $serverJson = [];
        foreach (Server::withTrashed()->get() as $server) {
            if (($forServers !== []) && (!in_array($server->id, $forServers))) {
                // Not one of the selected servers.
                continue;
            }

            // See if this is a deleted Realm.
            if ($server->trashed()) {
                // This server has been deleted, let Sphinx Node this server no longer exists.
                $serverJson[] = [
                    'id' => $server->id,
                    'deleted' => true
                ];

                continue;
            }

            // Generate whitelist JSON.
            $whitelistJson = [];
            foreach ($server->invited_players as $player) {
                $whitelistJson[] = [
                    'uuid' => $player->getFullUuid(),
                    'name' => $player->username
                ];
            }

            // Generate ops JSON.
            $opsJson = [];
            foreach ($server->operators as $player) {
                $opsJson[] = [
                    'uuid' => $player->getFullUuid(),
                    'name' => $player->username
                ];
            }

            $serverJson[] = [
                'id' => $server->id,
                'name' => $server->name,
                'jar' => env('MINECRAFT_JAR', 'minecraft_server.1.8.9.jar'),
                'active' => ($server->state == Server::STATE_OPEN),
                'properties' => [
                    'max-players' => env('SERVER_MAX_PLAYERS', 10),
                    'white-list' => 'true',
                    'motd' => $server->motd,
                    'op-permission-level' => (env('ENFORCE_OP_PERMISSIONS', true) ? '2' : '4')
                ],
                'whitelist' => $whitelistJson,
                'ops' => $opsJson,
                'needRestart' => $needRestart,
                'deleted' => false
            ];
        }

        // Send manifest.
        $this->connection->send(json_encode([
            'action' => 'manifest',
            'servers' => $serverJson
        ]));

        $this->connection->close();
    }

    /**
     * Request to join a server.
     * Returns the server's IP address.
     *
     * @param int $serverid
     */
    public function joinServer($serverid)
    {
        $this->connection->send(json_encode([
            'action' => 'join',
            'id' => $serverid
        ]));

        $resp = json_decode($this->connection->receive());
        return $resp->address;
    }

    /**
     * Get an array of statistics from the Sphinx Node.
     *
     * @return array
     * @throws \WebSocket\BadOpcodeException
     */
    public function stats()
    {
        $this->connection->send(json_encode([
            'action' => 'stats'
        ]));

        $resp = json_decode($this->connection->receive(), true);
        return $resp;
    }
}
