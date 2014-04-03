<?
//This is an API for the MoveableType API format

require_once( "MetaWeblogAPI.php" ); //MoveableType is a superset of MetaWeblog format, which is a superset of the Blogger format
require_once("../rss2feed.php");

class MoveableTypeAPI extends MetaWeblogAPI
{

	function MoveableTypeAPI( $XMLRequest )
	{
		$this->MetaWeblogAPI( $XMLRequest );
	}

	function mt_getcategorylist()
	{
		die( "not implemented" );
	}

}

