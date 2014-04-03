<?
//This is an API for the Wordpress API format

require_once("WordPressAPI.php");

$api = new WordPressAPI( $HTTP_RAW_POST_DATA );
$api->Execute();

