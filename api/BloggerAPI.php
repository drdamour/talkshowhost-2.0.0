<?
//This is an API for the blogger format

require_once("XMLRPCAPI.php");

class BloggerAPI extends XMLRPCAPI
{
	
	function BloggerAPI( $XMLRequest )
	{
		$this->XMLRPCAPI( $XMLRequest );

	}

	function blogger_getUsersBlogs()
	{
		/*

				<param>
				  <value>
					<array>
						<data>
							<value>
								<struct>
									<member>
										<name>isAdmin</name>
										<value>
											<boolean>1</boolean>
										</value>
									</member>
									<member>
										<name>url</name>
										<value>
											<string>http://blogs.pyramedia.com/drdamour/</string>
										</value>
									</member>
									<member>
										<name>blogid</name>
										<value><string>2</string></value>
									</member>
									<member>
										<name>blogName</name>
										<value>
											<string>DrDaMour Speaks</string>
										</value>
									</member>
								</struct>
							</value>
						</data>
					</array>
				</value>
			</param>
		*/

		global $apiuser, $apipass;

		$user = $this->Request->Parameters[2]->Value;
		$pass = $this->Request->Parameters[3]->Value;
		
		if( $user == $apiuser && $pass == $apipass )
		{
			?>
				<param>
				  <value>
					<array>
						<data>
							<value>
								<struct>
									<member>
										<name>isAdmin</name>
										<value>
											<boolean>1</boolean>
										</value>
									</member>
									<member>
										<name>url</name>
										<value>
											<string>http://talkshowhost.net/</string>
										</value>
									</member>
									<member>
										<name>blogid</name>
										<value>
											<string>1</string>
										</value>
									</member>
									<member>
										<name>blogName</name>
										<value>
											<string>And in the background, everything DaMour saw was gray</string>
										</value>
									</member>
								</struct>
							</value>
						</data>
					</array>
				</value>
			</param>

			<?

		}

	}
}

