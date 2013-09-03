<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// require_once("db.php");
require_once("kernel.php");
echo f($_REQUEST['type']);
?>
