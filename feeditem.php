<?php
	
	function OrderFeedsNewestToOldest($a, $b){
		if($a->Date == $b->Date) return 0;
		return ($a->Date < $b->Date) ? 1 : -1;
	}

	class feeditem{
		
		var $Author;
		var $Content;
		var $Link;
		var $Title;
		var $Date;
		var $Feed;
		var $Comments;
		var $Categories = array();

		function feeditem( $xmlarray, $feed){
			$this->Feed = $feed;

			foreach($xmlarray as $node){
				switch($node['tag']){

					case "link":
						$this->Link = $node['value'];
						break;

					case "title":
						$this->Title = $node['value'];
						break;

					case "pubDate":
						$this->Date = strtotime($node['value']);
						break;
					
					case "description":
						$this->Content = $node['value'];
						break;
					
					case "author":
						$this->Author = $node['value'];
						break;

					case "comments":
						$this->Comments = $node['value'];
						break;

					case "category":
						array_push( $this->Categories, $node['value'] );
						break;
				}

			}	

		}

	}

