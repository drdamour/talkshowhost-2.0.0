<?
//This is an API for the MetaWeblog API format

require_once("../config.php"); //get configuration
require_once( "XMLRPCAPI.php" ); 
require_once("../rss2feed.php");
require_once( "BloggerAPI.php" ); //MetaWeblog is a superset to the blogger format

class MetaWeblogAPI extends BloggerAPI
{

	function MetaWeblogAPI( $XMLRequest )
	{
		$this->BloggerAPI( $XMLRequest );
	}


	function metaWeblog_getRecentPosts( )
	{
		global $apiuser, $apipass;

		$user = $this->Request->Parameters[2]->Value;
		$pass =  $this->Request->Parameters[3]->Value;
		$count = $this->Request->Parameters[4]->Value;
		
		if( $user == $apiuser && $pass == $apipass )
		{
			?>
				<param>
					<value>
						<array>
							<data>
			<?
				$feed = new rss2feed("http://talkshowhost.net/rss.php");
				usort($feed->Items, "OrderFeedsNewestToOldest");
				
				$i = 0;

				foreach($feed->Items as $post)
				{
					
					print( "\n<value>" );
					print( "\n<struct>" );
					//20070921T17:05:31
					print( "\n<member><name>dateCreated</name><value><dateTime.iso8601>" . date( "Ymd\TH:i:s", $post->Date) . "Z</dateTime.iso8601></value></member>" );
					print( "\n<member><name>userid</name><value><string>1</string></value></member>" );
					print( "\n<member><name>postid</name><value><string>" . substr( $post->Comments, strrpos( $post->Comments, "=" ) ) . '</string></value></member>' );
					print( "\n<member><name>description</name><value><string>" .  $post->Content . '</string></value></member>' );
					print( "\n<member><name>title</name><value><string>" . $post->Title . '</string></value></member>' );
					print( "\n<member><name>link</name><value><string>" . $post->Link . '</string></value></member>' );
					print( "\n<member><name>permaLink</name><value><string>" . $post->Link . '</string></value></member>' );
					print( "\n<member><name>categories</name>" );
					print( "\n<value>" );
					print( "\n<array>" );
					print( "\n<data>" );

					//What happens if it's not a member of a category?
					foreach($post->Categories as $Category)
					{
						print( "\n<value><string>" . $Category . '</string></value>' );
					}
					print( "\n</data>" );
					print( "\n</array>" );
					print( "\n</value>" );
					print( "\n</member>" );
					print( "\n<member><name>mt_excerpt</name><value><string></string></value></member>" );
					print( "\n<member><name>mt_text_more</name><value><string></string></value></member>" );
					print( "\n<member><name>mt_allow_comments</name><value><int>1</int></value></member>" );
					print( "\n<member><name>mt_allow_pings</name><value><int>1</int></value></member>" );
					print( "\n<member><name>wp_slug</name><value><string>" . str_replace( " ", "-", $post->Title ) . '</string></value></member>' );
					print( "\n<member><name>wp_password</name><value><string></string></value></member>" );
					print( "\n<member><name>wp_author_id</name><value><string>1</string></value></member>" );
					print( "\n<member><name>wp_author_display_name</name><value><string>DrDaMour</string></value></member>" );
					print( "\n</struct>" );
					print( "\n</value>" );

					$i++;

					if($i = $count) break;

				}
			?>
							</data>
						</array>
					</value>
				</param>

			<?

		}

	}

	function metaWeblog_getCategories( )
	{
		die( "metaWeblog_getCategories not implemented" );

	}

	function metaWeblog_newPost( )
	{
		$blogid = $this->Request->Parameters[1]->Value;
		$user = $this->Request->Parameters[2]->Value;
		$password = $this->Request->Parameters[3]->Value;

		$publish = $this->Request->Parameters[5]->Value;

		die($blogid . " " . $user . " " . $password . "  " . $publish);
	}
}

