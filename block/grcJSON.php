<?php

function block()
{
    require_once('../GridcoinRPC.php');
    $gridcoin = new GridcoinRPC();

    echo $_GET;

    if (empty($_GET['height'])) {
        //getCurrentBlockCount
        $blockIndex = $gridcoin->multiCall(array(array("getblockcount", [])))["getblockcount"];
    } else {
        $blockIndex = $_GET["block"];
        $blockIndex = filter_var($blockIndex, FILTER_SANITIZE_NUMBER_INT);
        $blockIndex = filter_var($blockIndex, FILTER_VALIDATE_INT, array(
            'options' => array(
                'default' => 0,
                'min_range' => 0
            )));
    }

    return $gridcoin->multiCall(array(array("getblockbynumber", [$blockIndex])))["getblockbynumber"];
}