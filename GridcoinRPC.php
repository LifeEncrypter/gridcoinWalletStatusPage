<?php

class GridcoinRPC
{
    private array $curlOpts;

    /**
     * GridcoinRPC constructor.
     * Setup Curl Options array for reuse
     */
    public function __construct()
    {
        $config = include('config.php');

        $this->curlOpts = array(
            CURLOPT_URL            => "{$config['proto']}://{$config['host']}:{$config['rpcPort']}/{$config['url']}",
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => "{$config['rpcUser']}:{$config['rpcPass']}",
            CURLOPT_RETURNTRANSFER => true,
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
            'params' => $params
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