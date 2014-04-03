<?
//This is an API for the Wordpress API format

require_once( "MoveableTypeAPI.php" ); //Wordpress is a superset to the MoveableType format (which is a superset of MetaWeblog, which is a superset of Blogger)
require_once( "../Category.php" ); 

class WordPressAPI extends MoveableTypeAPI
{

	function WordPressAPI( $XMLRequest )
	{
		$this->MoveableTypeAPI( $XMLRequest );
	}


	function wp_getCategories( )
	{
		$categories = Array();
		$cat = new Category();
		$cat->ID = 1;
		$cat->ParentID = 0;
		$cat->RSS = "";
		$cat->URL = "";
		$cat->Name = "Name";
		$cat->Description = "Description";

		array_push( $categories, $cat );

		global $apiuser, $apipass;
		
		$user = $this->Request->Parameters[2]->Value;
		$pass =  $this->Request->Parameters[3]->Value;

		if( $user == $apiuser && $pass == $apipass )
		{
			?>
				<param>
					<value>
						<array>
							<data>
			<?
				foreach($categories as $category)
				{
					print( "<value>\n" );
					print( "\n<struct>" );
					print( "\n<member><name>categoryId</name><value><string>" . $category->ID . '</string></value></member>' );
					print( "\n<member><name>parentId</name><value><string>" . $category->ParentID . '</string></value></member>' );
					print( "\n<member><name>description</name><value><string>" . $category->Description . '</string></value></member>' );
					print( "\n<member><name>categoryName</name><value><string>" . $category->Name . '</string></value></member>' );
					print( "\n<member><name>htmlUrl</name><value><string>" . $category->URL . '</string></value></member>' );
					print( "\n<member><name>rssUrl</name><value><string>" . $category->RSS . '</string></value></member>' );
					print( "\n</struct>" );
					print( "\n</value>" );
				}
			?>
							</data>
						</array>
					</value>
				</param>


			<?

		}

	}
}

