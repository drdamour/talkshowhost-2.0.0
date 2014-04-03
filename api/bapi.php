<?
//This is an API for the blogger format

require_once("BloggerAPI.php");

$api = new BloggerAPI( $HTTP_RAW_POST_DATA );
$api->Execute();
