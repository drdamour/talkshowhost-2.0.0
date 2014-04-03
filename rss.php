<?php
	require_once("feedcreator.class.php");
	require_once("db-connect.php");
	
	header('Content-type: text/xml');

	$rss = new UniversalFeedCreator(); 
	//$rss->useCached(); 
	$rss->title = "And in the backgound, everything DaMour saw was gray"; 
	$rss->description = "DrDaMour & DaMour"; 
	$rss->link = "http://www.talkshowhost.net/"; 
	$rss->syndicationURL = "http://www.talkshowhost.net/" . $PHP_SELF; 

	$image = new FeedImage(); 
	$image->title = "DrDaMour"; 
	$image->url = "http://www.talkshowhost.net/images/doctordamour.gif"; 
	$image->link = "http://www.talkshowhost.net/"; 
	$image->description = "MuaHaHaHaHa"; 
	$rss->image = $image; 

	 //ISO 8601 Date Format:
	 //"2003-01-20T18:05:41+04:00"

	/*
		RSS HTTP GET API
		Query Param - Values - Example - What it does
		ids - comma seperated list of ids - 30,4,5 - Gets only the posts with those ids
		idranges - comma seperate list of ranges - 5-10,20-30 - Gets only the posts with ids in that rage inclusive
	*/

	$sql = "SELECT title, DATE_FORMAT( last_updated, '%Y-%m-%dT%H:%i:%s') as last_updated, content, id FROM news WHERE status = '1'";
	
	//limit to a set of ids?
	if( isset( $_GET['ids'])  )
	{
		//Do some validation, first split the ids
		$streamids = explode( ",", $_GET['ids'] );
		foreach( $streamids as $id )
		{
			if( !is_numeric( $id ) )
			{
				//die
				die( "Invalid ids parameter specified, must be a comma delimitted list of integers. found: " . $id );
			}
		}

		if( sizeof( $streamids ) == 0 )
		{
			die( "Invalid ids parameter specified, , must be a comma delimitted list of integers. no values found." );
		}

		$sql .= " AND id IN ( " . implode( ",", $streamids )  . ")";
	}

	//limit to a set of ranges of ids?
	if( isset( $_GET['idranges'] ) )
	{
		$rangefilter = "(1 = 0)";

		$idranges = explode( ",", $_GET['idranges'] );
		foreach($idranges as $idrange)
		{
			//Check to make sure id's are valid
			$idrangeends = explode( "-", $idrange );
			if( sizeof($idrangeends) != 2 )
			{
				die( "Invalid idranges specified for idrange " . $idrange . " found " . sizeof($idrangeends) . " endpoints");
			}
			
			
			if( !is_numeric( $idrangeends[0] ) )
			{
				die( "Invalid start " . $idrangeends[0] . " of idrange  " . $idrange );
			}

			if( !is_numeric( $idrangeends[0] ) )
			{
				die( "Invalid end " . $idrangeends[1] . " of idrange  " . $idrange );
			}

			//OK add the range SQL
			//Need to find which is bigger
			if( $idrangeends[1] >= $idrangeends[0] )
			{
				//normal case
				$rangefilter .= " OR (id >= " . $idrangeends[0] . " AND id <= " . $idrangeends[1] . ")";
			}
			else
			{
				//reverse case
				$rangefilter .= " OR (id >= " . $idrangeends[1] . " AND id <= " . $idrangeends[0] . ")";
			}
		}

		$sql .= " AND (" . $rangefilter . ")";
	}

	$sql .= " ORDER BY last_updated DESC";
	$sql .= " LIMIT 0,10";

	$db = new db();
	$result = mysql_query( $sql ) or die(mysql_error());
	while ($row = mysql_fetch_assoc($result)) { 
		$item = new FeedItem(); 
		$item->title = $row["title"];

		$url_encoded_title = str_replace( " ", "-", $item->title);
		$url_encoded_title = urlencode( $url_encoded_title );

		$item->link = "http://www.talkshowhost.net/main/talk/sub/archives/" . $row['id'] . "/" . $url_encoded_title . "/";		
		$item->description = nl2br( $row["content"] );
		$item->comments = $item->link . "#comments";

		$item->date = $row["last_updated"]; 
		$item->source = "http://www.talkshowhost.net/main/talk/sub/archives/" . $row['id'] . "/". $url_encoded_title; 
		$item->author = "DrDaMour"; 
		 
		$tags_result = mysql_query("SELECT t.TAG TAG FROM news_tags_links l LEFT JOIN news_tags t on l.TAG_ID = t.ID WHERE l.NEWS_ID = " . $row['id']);
		while ($tag = mysql_fetch_assoc($tags_result)) {
			$item->AddCategory( $tag['TAG'] );
		}
		$rss->addItem($item); 
	} 

	$rss->_setFormat("RSS2.0");
	print($rss->_feed->createFeed());

	$db->close();
