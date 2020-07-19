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
            CURLOPT_URL            => "{$config['proto']}://{$config['host']}:{$config['rpcPort']}/{$config['urlPath']}",
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => "{$config['rpcUser']}:{$config['rpcPass']}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POST           => true
        );
    }

    /**
     * Execute multiple gridcoin RPC calls asynchronously
     * @param $rpcArray
     * $rpcArray = array(
     *      array("method_1", [params_1]),
     *      array("method_2", [])
     * );
     *
     * params must also be an array
     * Multiple params example:
     *      array("listpollresults", ["Whitelist_Ibercivis", true])
     *
     * @return array|bool
     * array of run methods where success, false where single method fails.
     * returns false if curl_multi fails
     */
    public function multiCall($rpcArray)
    {
        $curlMulti = curl_multi_init();
        $curlHandles = array();

        foreach ($rpcArray as $rpc)
        {
            $curlHandles[$rpc[0]] = curl_init();

            curl_setopt_array($curlHandles[$rpc[0]], $this->curlOpts);

            $postParams = json_encode(array(
                'method' => $rpc[0],
                'params' => $rpc[1]
            ));
            curl_setopt($curlHandles[$rpc[0]], CURLOPT_POSTFIELDS, $postParams);

            curl_multi_add_handle($curlMulti, $curlHandles[$rpc[0]]);
        }

        //execute async curl calls
        do {
            $multiStatus = curl_multi_exec($curlMulti, $active);
            curl_multi_select($curlMulti);
        } while ($active > 0);

        if($multiStatus == CURLM_OK)
        {
            $result = array();

            foreach ($rpcArray as $rpc)
            {
                $response = curl_multi_getcontent($curlHandles[$rpc[0]]);

                $jsonResponse = json_decode($response, true);

                $curlStatus = curl_getinfo($curlHandles[$rpc[0]], CURLINFO_HTTP_CODE);

                $error = false;
                if ($curlStatus != 200)
                {
                    $error = true;
                    $httpError = curl_error($curlHandles[$rpc[0]]);
                    error_log("HttpError: $httpError");
                }

                curl_multi_remove_handle($curlMulti, $curlHandles[$rpc[0]]);
                curl_close($curlHandles[$rpc[0]]);

                if ($jsonResponse['error'])
                {
                    $error = true;
                    $rpcError = $result[$rpc[0]]['error']['message'];
                    error_log("RPCError: $rpcError");
                }

                if(!$error)
                {
                    $result[$rpc[0]] = $jsonResponse['result'];
                } else
                {
                    $result[$rpc[0]] = FALSE;
                }
            }

            curl_multi_close($curlMulti);
            return $result;
        } else
        {
            echo "error in multi";
            return FALSE;
        }
    }
}