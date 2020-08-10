<?php
require_once('GridcoinRPC.php');
$gridcoin = new GridcoinRPC();

$newestblock = $gridcoin->multiCall(array(array("getblockcount", [])));

$calls = array(
    //array("getinfo", []),
    array("getnetworkinfo", []),
    array("showblock", [$newestblock["getblockcount"]]),
    array("getwalletinfo", [])
);

$data = $gridcoin->multiCall($calls);

//$InfoPriv = array("ip", "proxy", "balance", "stake", "newmint");
$networkInfoPriv = array("ip", "proxy",);

foreach ($networkInfoPriv as $entry)
{
    unset($data['getnetworkinfo']["$entry"]);
}

if(array_key_exists('unlocked_until', $data['getwalletinfo']) || array_key_exists('staking', $data['getwalletinfo']))
{
    if ($data['getwalletinfo']['unlocked_until'] != 0 || $data['getwalletinfo']['staking'] == true)
    {
        unset($data['getwalletinfo']);
        $data['getwalletinfo']['staking'] = true;
    } else
    {
        unset($data['getwalletinfo']);
        $data['getwalletinfo']['staking'] = false;
    }
} else
{
    unset($data['getwalletinfo']);
}

header('Content-Type: application/json');

echo json_encode($data);