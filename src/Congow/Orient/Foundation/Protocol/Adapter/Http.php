<?php

/*
 * This file is part of the Congow\Orient package.
 *
 * (c) Alessandro Nadalin <alessandro.nadalin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class Http
 *
 * @package     
 * @subpackage  
 * @author      Alessandro Nadalin <alessandro.nadalin@gmail.com>
 */

namespace Congow\Orient\Foundation\Protocol\Adapter;

use Congow\Orient\Contract\Protocol\Adapter as ProtocolAdapter;
use Congow\Orient\Contract\Http\Client;
use Congow\Orient\Foundation\Binding;

class Http implements protocolAdapter
{
    /**
     * Instantiates a new adapter.
     *
     * @api
     * @param Http\Client $client
     * @param String $host
     * @param String $port
     * @param String $username
     * @param String $password
     * @todo better to inject the binding
     */
    public function __construct(Client $client, $host = '127.0.0.1', $port = 2480, $username = null, $password = null, $database = null)
    {
        $this->client = new Binding($client, $host, $port, $username, $password, $database);
    }
    
    /**
     * Executes some SQL against Congow\OrientDB via the HTTP binding.
     *
     * @param   string $sql
     * @return  mixed
     * @throws  \Exception
     * @todo should return StdObject for SELECT and stuff that retrieves data, true otherwise
     * @todo throw a specific exception
     * @exploding the status code should not be done hete, need to add an option to
     * ->getStatusCode($numeric) to return only 200 instead of HTTP/1.1 200 OK
     */
    public function execute($sql)
    {
        $method = 'command';
        
        $parts   = explode(' ', $sql);
        $command = strtolower($parts[0]);
        
        if ($command == 'select') {
          $method = 'query';
        }
        
        $response = $this->client->$method($sql);
        
        if (!$response) {
            throw new \Exception('Unable to retrieve a response');
        }
        
        $statusCode = explode(' ', $response->getStatusCode());
        
        /**
         * @todo ugly
         */
        if ($statusCode[1][0] == 2) {
            if($command == 'select'){
                $body = json_decode($response->getBody());
                
                return $body->result;
            }
            return true;
        }
        
        throw new \Exception($response->getBody());
    }
    
    /**
     * @todo phpdoc
     */
    // public function find($rid){
    //     $result =  $this->execute('SELECT FROM ' . $rid);
    //     
    //     if (is_array($result) && count($result) == 1){
    //         return array_shift($result);
    //     }
    //     
    //     return null;
    // }
}

