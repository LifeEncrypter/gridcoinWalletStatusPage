<?php

class GridcoinRPC extends Thread
{
    //private $host;
    //private $port;
    //private $rpcUser;
    //private $rpcPass;
    //private $proto;

    private array $curlOpts;

    /**
     * GridcoinRPC constructor.
     */
    public function __construct()
    {
        $config = include('config.php');

        //$this->host     = $config['host'];
        //$this->port     = $config['port'];
        //$this->rpcUser  = $config['rpcUser'];
        //$this->rpcPass  = $config['rpcPass'];
        //$this->proto    = $config['proto'];

        $this->curlOpts = array(
            CURLOPT_URL            => "{$config['proto']}://{$config['host']}:{$config['rpcPort']}/{$config['url']}",
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => "{$config['rpcUser']}:{$config['rpcPass']}",
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POST           => true
        );
    }

    public function __call($method, $params)
    {
        $curlCall = curl_init();
        $error = false;

        curl_setopt_array($curlCall, $this->curlOpts);

        $postParams = json_encode(array(
            'method' => $method,
            'params' => $params//,
            //'id'     => $this->id
        ));

        curl_setopt($curlCall, CURLOPT_POSTFIELDS, $postParams);

        $response = curl_exec($curlCall);
        $jsonResponse = json_decode($response, true);
        $status = curl_getinfo($curlCall, CURLINFO_HTTP_CODE);

        if ($status != 200)
        {
            $error = true;
            $httpError = curl_error($curlCall);
            error_log("HttpError: $httpError");
        }

        curl_close($curlCall);

        if ($jsonResponse['error'])
        {
            $error = true;
            $rpcError = $jsonResponse['error']['message'];
            error_log("RPCError: $rpcError");
        }

        if ($error)
        {
            return false;
        }

        return $jsonResponse['result'];
    }
}