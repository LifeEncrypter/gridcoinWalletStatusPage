<?php
require_once('grcJSON.php');

$block = block();

header('Content-Type: application/json');

echo json_encode($block);