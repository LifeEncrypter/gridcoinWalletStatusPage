<?php
require_once('GridcoinRPC.php');
$gridcoin = new GridcoinRPC();

$newestblock = $gridcoin->multiCall(array(array("getblockcount", [])));

$calls = array(
    //array("getinfo", []),
    array("getnetworkinfo", []),
    array("showblock", [$newestblock["getblockcount"]])
);

$data = $gridcoin->multiCall($calls);

//$InfoPriv = array("ip", "proxy", "balance", "stake", "newmint");
$networkInfoPriv = array("ip", "proxy",);

foreach ($networkInfoPriv as $entry)
{
    unset($data["getnetworkinfo"]["$entry"]);
}

header('Content-Type: application/json');

echo json_encode($data);