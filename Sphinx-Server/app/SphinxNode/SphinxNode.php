<?php

namespace App\SphinxNode;

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
}
