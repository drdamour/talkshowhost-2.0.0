<?
//This is an API for the blogger format

require_once("XMLRPCParser.php");

class XMLRPCAPI
{
	var $Request;
	var $writer;

	function XMLRPCAPI( $XMLRequest )
	{
		$this->Request = new XMLRPCParser( $XMLRequest );
	}

	function Execute( )
	{
		print( '<?xml version="1.0" encoding="utf-8"?>' );
		print( "\n<methodResponse>\n" );
		print( "\t<params>\n" );

		$method = str_replace( ".", "_", $this->Request->Method );
		$this->$method();

		print( "\t</params>\n" );
		print( "</methodResponse>" );
	}
}
