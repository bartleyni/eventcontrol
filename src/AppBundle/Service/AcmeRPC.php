<?php

namespace Acme\HelloBundle\Service;

use Ratchet\ConnectionInterface;
use Gos\Bundle\WebSocketBundle\RPC\RpcInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class AcmeRPC implements RpcInterface
{
    /**
     * Adds the params together
     *
     * Note: $conn isnt used here, but contains the connection of the person making this request.
     *
     * @param ConnectionInterface $connection
     * @param WampRequest $request
     * @param array $params
     * @return int
     */
    public function addFunc(ConnectionInterface $connection, WampRequest $request, $params)
    {
        return array("result" => array_sum($params));
    }
    
    /**
     * Name of RPC, use for pubsub router (see step3)
     * 
     * @return string
     */
    public function getName()
    {
        return 'acme.rpc';
    }
}