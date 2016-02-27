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
        foreach (Server::all() as $server) {
            if (($forServers !== []) && (!in_array($server->id, $forServers))) {
                // Not one of the selected servers.
                continue;
            }

            if (!$server->isInvited(Player::current())) {
                // Not invited.
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
                    'motd' => $server->motd
                ],
                'whitelist' => $whitelistJson,
                'ops' => $opsJson,
                'needRestart' => $needRestart
            ];
        }

        // Send manifest.
        $this->connection->send(json_encode([
            'action' => 'manifest',
            'servers' => $serverJson
        ]));

        $this->connection->close();
    }
}
