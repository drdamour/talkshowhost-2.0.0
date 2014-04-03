<?
//This is an API for the MoveableType format

require_once("MoveableTypeAPI.php");

$api = new MoveableTypeAPI( $HTTP_RAW_POST_DATA );
$api->Execute();

