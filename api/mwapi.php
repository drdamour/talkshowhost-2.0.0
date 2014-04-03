<?
//This is an API for the blogger format

require_once("MetaWeblogAPI.php");

$api = new MetaWeblogAPI( $HTTP_RAW_POST_DATA );
$api->Execute();

