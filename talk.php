<?php

require_once("button.php");
require_once("db-connect.php");
require_once("rss2feed.php");

class talk extends button{

	function talk(){
		$this->button();
	}


	function blog($max = 10){

		$feed = new rss2feed("http://talkshowhost.net/rss.php");
			
		//Need to sort $feed->Items by some algorhythm, we'll sort newest to oldest
		usort($feed->Items, "OrderFeedsNewestToOldest");

		foreach($feed->Items as $item){

			print('<div class="log">');
					
				//Haven't found a good spot for the image currently
				//print('<span class="log-feed-image" style="margin-right: 10px;"><a href="' . $item->Feed->Image->Link . '" target="_blank"><img border="0" src="' .  $item->Feed->Image->URI .'" alt="' .  $item->Feed->Image->Description .'"/></a></span>');

				print('<div class="log-title"><a href="' . $item->Link . '">' . $item->Title . '</a></div>');
			
				print('<div class="log-time" style="clear: both;">');
				
				print('On The <acronym title="' . date( "l F jS, Y", $item->Date) . '">' . cardinal( date( "z", $item->Date ) ) . '</acronym> Day of the ' . cardinal( date( "Y", $item->Date ) ) . ' Year ' . $item->Author . ' Wrote');

				print('</div>');

				print('<div class="log-content">' . nl2br( $item->Content ) . '</div>');
					
				print("\n\n");

				print('<div class="log-footer"><a href="' . $item->Link . '">permalink</a> | ');
					
				if( $item->Comments == "")
				{
					//No comments link available
					print('Comments Unavailable');
				}
				else
				{
					print('<a href="' . $item->Comments . '">comments</a> ');
				}
					
				print('| via <a href="' . $item->Feed->Link . '">' . $item->Feed->Title . '</a>');
					
				if(Count($item->Categories) > 0){
					print(' | tags: ');
					foreach($item->Categories as $Category){
						print( '' . $Category . ' ' ); //maybe links one day
					}
				}

				print('</div>');
				print("</div>\n\n");

			}
			
			print("<br/><div align='center'><a href='/main/talk/sub/archives/'>older blogs</a></div>");
		
	}

	function archives()
	{
		//If the a specific id is set, show that one, otherwise we should show some archive searching feature or something...whatever
		
		if( isset( $_GET['streamid'] ) ){
			$feed = new rss2feed("http://talkshowhost.net/rss.php?ids=" . $_GET['streamid'] );
			usort($feed->Items, "OrderFeedsNewestToOldest");
			foreach($feed->Items as $item){

				print('<div class="log">');
					
				//Haven't found a good spot for the image currently
				//print('<span class="log-feed-image" style="margin-right: 10px;"><a href="' . $item->Feed->Image->Link . '" target="_blank"><img border="0" src="' .  $item->Feed->Image->URI .'" alt="' .  $item->Feed->Image->Description .'"/></a></span>');

				print('<div class="log-title">' . $item->Title . '</div>');
		
				print('<div class="log-time" style="clear: both;">');
			
				print('On The <acronym title="' . date( "l F jS, Y", $item->Date) . '">' . cardinal( date( "z", $item->Date ) ) . '</acronym> Day of the ' . cardinal( date( "Y", $item->Date ) ) . ' Year ' . $item->Author . ' Wrote');


				print('</div>');


				print('<div class="log-content">' . nl2br( $item->Content ) . '</div>');
				
				print("\n\n");

				print('<div class="log-footer"><a href="' . $item->Link . '">permalink</a>');
				
				print(' | via <a href="' . $item->Feed->Link . '">' . $item->Feed->Title . '</a>');
				
				if(Count($item->Categories) > 0){
					print(' | tags: ');
					foreach($item->Categories as $Category){
						print( '' . $Category . ' ' ); //maybe links one day
					}
				}

				print('</div>');

				if( $item->Comments == "")
				{
					//No comments link available
					print('Comments Unavailable');
				}
				else
				{
					print( '<a name="comments"></a><div id="disqus_thread"></div><script type="text/javascript" src="http://disqus.com/forums/talkshowhost/embed.js"></script><noscript><a href="http://talkshowhost.disqus.com/?url=ref">View the forum thread.</a></noscript>' );
				}
				
				print("</div>\n\n");

			}

		}
		elseif( isset( $_GET['streamrange'] ) ) //Diplay a range if requested
		{
			$feed = new rss2feed("http://talkshowhost.net/rss.php?idranges=" . $_GET['streamrange'] );
			usort($feed->Items, "OrderFeedsNewestToOldest");
			foreach($feed->Items as $item){

				print('<div class="log">');
					
					//Haven't found a good spot for the image currently
					//print('<span class="log-feed-image" style="margin-right: 10px;"><a href="' . $item->Feed->Image->Link . '" target="_blank"><img border="0" src="' .  $item->Feed->Image->URI .'" alt="' .  $item->Feed->Image->Description .'"/></a></span>');

					print('<div class="log-title">' . $item->Title . '</div>');
			
					print('<div class="log-time" style="clear: both;">');
				
					print('On The <acronym title="' . date( "l F jS, Y", $item->Date) . '">' . cardinal( date( "z", $item->Date ) ) . '</acronym> Day of the ' . cardinal( date( "Y", $item->Date ) ) . ' Year ' . $item->Author . ' Wrote');


					print('</div>');


					print('<div class="log-content">' . nl2br( $item->Content ) . '</div>');
					
					print("\n\n");

					print('<div class="log-footer"><a href="' . $item->Link . '">permalink</a> | ');
					
					if( $item->Comments == ""){
						//No comments link available
						print('Comments Unavailable');
					}
					else{
						print('<a href="' . $item->Comments . '" target="_blank" onclick="return showComments(\'' . $item->Comments . '\', this.parentNode.parentNode);">see comments</a> ');
					}
					
					print('| via <a href="' . $item->Feed->Link . '">' . $item->Feed->Title . '</a>');
					
					if(Count($item->Categories) > 0){
						print(' | tags: ');
						foreach($item->Categories as $Category){
							print( '' . $Category . ' ' ); //maybe links one day
						}
					}

				print('</div>');
				print("</div>\n\n");
			}
		}
		else
		{
			print("Archive Browsing to come!");
		}

		
			
			

			

	}

	function pending(){
		print("<div>This is a List of Blogs I've started, but are still under review & need major edits</div>");

		$db = new db();
		$query = "SELECT title, TO_DAYS(NOW()) - TO_DAYS(created) as days_pending, TO_DAYS(NOW()) - TO_DAYS(created) as days_lastmod FROM news WHERE status = '0' ORDER BY days_pending DESC";
		
		$result = mysql_query($query, $db->getLink())	or die("could not connect: " . mysql_error());

		print('<div>');

		while($row = mysql_fetch_assoc($result)){
			print('<div style="margin-top: 20px;">');
			print('<div>' . $row['title'] . '</div>');
			print('<div>Pending for ' . $row['days_pending'] . ' days</div>');
			print('<div>Updated ' . $row['days_lastmod'] . ' days ago</div>');
			print('</div>');
		}

		print("</div>");

	}


}
