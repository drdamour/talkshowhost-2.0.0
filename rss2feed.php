<?php
	require_once("feeditem.php");

	class rss2feedImage{

		var $URI;
		var $Title;
		var $Link;
		var $Description;

		function rss2feedImage( $xmlarray ){

			foreach($xmlarray as $node){
				switch($node['tag']){

					case "url":
						$this->URI = $node['value'];
						break;

					case "title":
						$this->Title = $node['value'];
						break;

					case "link":
						$this->Link = $node['value'];
						break;
					
					case "description":
						$this->Description = $node['value'];
						break;
				}

			}
		}

	}

	class rss2feed{

		var $Title;
		var $Items;
		var $Image;
		var $Link;

		function rss2feed( $URI ){
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, file_get_contents( $URI ), $values, $tags);

			$this->Image = new rss2feedImage( array_slice($values, $tags['image'][0] + 1, $tags['image'][1] - $tags['image'][0]) );
			
			//print_r($tags);
			//print_r($values);

			$this->Items = array();

			//Title of the feed is the only 3rd level title
			foreach($tags['title'] as $index){
				if($values[$index]['level'] == 3){
					$this->Title = $values[$index]['value'];
					break;
				}
			}

			//Link of the feed is the only 3rd level Link
			foreach($tags['link'] as $index){
				if($values[$index]['level'] == 3){
					$this->Link = $values[$index]['value'];
					break;
				}
			}

			for($i = 0; $i < SizeOf($tags['item']); $i = $i + 2){
				array_push( $this->Items, new feeditem( array_slice( $values, $tags['item'][$i]+1, $tags['item'][$i + 1] - $tags['item'][$i] ) , $this) );
			}


		}



	}

