<?
//this is an XML RPC Parser, it parses the method calls and parameters
//see: http://us3.php.net/manual/en/function.xml-parse-into-struct.php for how this works

function XMLRPCParameterFactory( $Nodes )
{
	$valueopen = -1;
	$level = -1;
	$name;

	for($i = 0; $i < sizeof($Nodes); $i++ )
	{
		if( $Nodes[$i]['tag'] == 'name' )
		{
			$name = $Nodes[$i]['value'];
		}

		if( $Nodes[$i]['tag'] == 'value' )
		{
			if($valueopen == -1)
			{
				$valueopen = $i;
				$level = $Nodes[$i]['level'];
			}
			else if($level == $Nodes[$i]['level'])
			{
				$typenode = $Nodes[$valueopen + 1];

				switch($typenode['tag'])
				{
					case "string":
						return new XMLRPCStringParameter( $typenode['value'], $name );

					case "boolean":
						return new XMLRPCBooleanParameter( $typenode['value'], $name );

					case "struct":
						return new XMLRPCStructParameter( array_slice($Nodes, $valueopen + 1, $i - $valueopen - 1), $name );
						
					case "array":
						return new XMLRPCArrayParameter( "", $name );
						
					default:
						die("XML RPC Parameter type " . $typenode['tag'] . " not recognized");
				}
				
				$valueopen = -1;
			}
		}
	}

}


class XMLRPCParameter
{
	var $Type;
	var $Value;
	var $Name;

	function XMLRPCParameter( $name )
	{
		$this->Name = $name;
	}
}

class XMLRPCStringParameter extends XMLRPCParameter
{

	function XMLRPCStringParameter( $Value, $name )
	{
		$this->XMLRPCParameter( $name );
		$this->Type = "string";
		$this->Value = $Value;
	}

}

class XMLRPCBooleanParameter extends XMLRPCParameter
{
	var $Type;
	var $Value;

	function XMLRPCBooleanParameter( $Value, $name )
	{
		$this->XMLRPCParameter( $name );
		$this->Type = "boolean";
		$this->Value = ($Value == 1);
	}
}

class XMLRPCArrayParameter extends XMLRPCParameter
{
	var $Type;
	var $Value;

	function XMLRPCArrayParameter( $Value, $name )
	{
		$this->XMLRPCParameter( $name );
		$this->Type = "array";
		//$this->Value = ($Value == 1);
	}
}

class XMLRPCStructParameter extends XMLRPCParameter
{

	function XMLRPCStructParameter( $MemberNodes, $name )
	{
		$this->XMLRPCParameter( $name );

		$this->Type = "struct";
		$this->Value = Array();

		$open = -1; //track the open edge
		for($i = 0; $i < sizeof($MemberNodes); $i++)
		{
			//see if we are at a member edge
			if( $MemberNodes[$i]['tag'] == 'member' )
			{
				if($open == -1)
				{
					$open = $i; //track the open edge for this instance
				}
				else
				{
					//Ok we found the close edge
					$param = XMLRPCParameterFactory( array_slice( $MemberNodes, $open +1, $i - $open -1 ) );
					$this->Value[$param->Name] = $param;
					$open = -1;
				}
			}
		}

		if($open != -1) die ("missing a member close tag");
	}
}

class XMLRPCParser
{
	var $Parameters;
	var $Method;

	function XMLRPCParser( $XMLData )
	{
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

		xml_parse_into_struct($parser, $XMLData, $values, $tags);

		//print_r($tags);
		//print_r($values);

		$this->Method = $values[$tags["methodName"][0]]["value"];

		$this->Parameters = Array();

		$i = 1;
		
		for($j = 0; $j < sizeof($tags["param"]); $j = $j + 2)
		{
			//This logic asssumes there is only one node directly under param (namely value),and that there is one node directly under value (the type node)

			$paramopenindex = $tags["param"][$j];
			$paramcloseindex = $tags["param"][$j+1];

			$this->Parameters[$i] = XMLRPCParameterFactory( array_slice( $values, $paramopenindex + 1, $paramcloseindex - $paramopenindex - 1) );
			$i++;

		}
		
	}


}
