<?php

require_once("button.php");
require_once("db-connect.php");
require_once("select.php");


	class show extends button{

		
		function show(){
			$this->button();
		}
	

		function links(){
			$selectLista = new select("sorta", "category");
			$selectLista->addOption( new option("Category", "category") );
			$selectLista->addOption( new option("Clicks", "clicks DESC") );
			$selectLista->addOption( new option("Rating", "rating DESC") );
			$selectLista->addOption( new option("Newest", "added DESC") );
			$selectLista->addOption( new option("Oldest", "added") );
			$selectLista->addOption( new option("Votes", "votes DESC") );
			
			$selectListb = new select("sortb", "rating DESC");
			$selectListb->addOption( new option("Category", "category") );
			$selectListb->addOption( new option("Clicks", "clicks DESC") );
			$selectListb->addOption( new option("Rating", "rating DESC") );
			$selectListb->addOption( new option("Newest", "added DESC") );
			$selectListb->addOption( new option("Oldest", "added") );
			$selectListb->addOption( new option("Votes", "votes DESC") );
			
			
			print("<div style='text-align: center;'><form method='get' action='/main/show/sub/links/'>\n\n");
			//make sure we go to the links page
			print( "sort by " . $selectLista->getHTML() . " then by " . $selectListb->getHTML() );
			
			print("<input type=submit value='go'></form></div>");

			$db = new db();

			$query = "SELECT COUNT(lc.link_id) as clicks, lr.votes AS votes, a.date AS date, DATE_FORMAT(a.date,'%c/%e/%y') AS added, a.url, a.title, a.description, b.name AS category, a.id AS id, lr.rating AS rating FROM links AS a LEFT JOIN links_category AS b ON a.category = b.id LEFT JOIN links_clicks AS lc ON a.id = lc.link_id LEFT JOIN links_avg_rating AS lr ON a.id=lr.link_id GROUP BY a.id ORDER BY " . $selectLista->getValue() . ", " . $selectListb->getValue();
			

			$result = mysql_query($query, $db->getLink()) or die("could not connect: " . mysql_error());

			print("\n\n");

			$spliter = explode(" ", $selectLista->getValue());
			$spliter = $spliter[0];

			$grouper = '';
			while($row = mysql_fetch_assoc($result)){
				if($grouper != $row[$spliter]){

					if($grouper != '') { print("</div>"); }

					print("\n" . "<div class='link-category'>"  );

					print("<div class='link-category-header'>$spliter: " . $row[$spliter] . "</div>");
					
					$grouper = $row[$spliter];
				}

				//Item Container START
				print("<div class='link-item' >\n");

				//Header Container START
				print( "<a href='/go/$row[id]' target='_blank'><div class='link-item-header' onMouseOver=\"this.style.backgroundColor='red'; \" onMouseOut=\"this.style.backgroundColor='#3399CC' \">" . $row['title'] . "</div></a>" );
				//Header Container END

				print("<div class='link-item-stats'>");
				
				print( "<div>" . number_format($row['rating'], 3) . " rating</div>" );
				
				print( "<div>" . $row['votes'] . " vote");
				if($row['votes'] > 1) print("s");
				print("</div>" );
				

				print( "<div>" . $row['clicks'] . " click");
				if($row['clicks'] > 1) print("s");
				print("</div>" );		

				print( "<div>since " . $row['added'] . "</div>" );	

				print("</div>");


				//Item Text START
				
				print( "<div style='width: 100%; clear: left; text-align: justify; padding: 8px;'>" . nl2br($row['description']) . "</div>" );
				
				//Item Text END

	/*
				print("<form method=post action='ratelink.php' target='_blank'>");

				print("<select style='font-size: 8pt;' name='rating' onChange='this.form.submit()'>");

				for($k = 10; $k > -1; $k--){
					print("<option value='$k'>$k</option>");
				}
				
				print("</select><input type=hidden name=id value='$row[id]' /><noscript><input type=submit value='Rate It' /></noscript></form>");
*/

				print("<div style='clear: both;'></div>");  //make parent contain child
				print("</div>\n\n");
				//Item Container END
			
			}

			print("</div>");

			$db->close();

			print("<div align=center><form method='get' action='/main/show/sub/links/'>");
		
			print( "sort by " . $selectLista->getHTML() . " then by " . $selectListb->getHTML() );
		
			print("<input type=submit value='go'></form></div>");

		}


		function wishlist(){
			$db = new db();
			
			print('<div>Externals: <a href="http://www.thinkgeek.com/brain/gimme.cgi?wid=81d29aa38" target="_blank">thinkgeek</a> | <a href="http://www.amazon.com/gp/registry/registry.html/ref=wlem-si-html_viewall/104-5391579-0155957?id=33VE1U5D3LOQR " target="_blank">amazon</a></div>');

			//First display items needed
			$query = "SELECT w.*, l.url FROM `wishlist` AS w LEFT JOIN `wishlist_links` AS l ON w.id = l.item_id WHERE w.status = 0 order by w.rating, w.id";

			$result = mysql_query($query, $db->getLink() ) or die(mysql_error());
			
			print('<div class="wishlist-category-title">Wanted: Dead or Alive</div><div style="clear: both;"></div>');
			print('<div class="wishlist-table"><table border="0" cellspacing="1" cellpadding="0">');
			
			$total_estimate = 0;
			
			$current_id = -1;
			$price = 0;

			while($data = mysql_fetch_assoc($result) ){
				if($current_id != $data['id']){
					//If it's a new item i need to close the old item
								
					if($current_id != -1){
						//but only if this isn't the first item
						print('</div></td>');
						print('<td style="text-align: right;">' . number_format($price, 2) . '</td>');
						print('</tr>');
					}
					
					//Start the new item
					$current_id = $data['id'];
					
					$price = $data['price'];
					$total_estimate += $price;

					print('<tr>');
					print('<td style="padding-right: 10px;"><div class="wishlist-item-title">' . $data['title'] . '</div>');
					print('<div class="wishlist-item-description">' . $data['description'] . '</div></td>' );

					$link_count = 0;
					print('<td align="center"><div class="wishlist-item-links"><a href="' . $data[url] . '" target="_blank">link' .  ++$link_count . '</a>');
				}
				else{
					//it's the same item
					print(' <a href="' . $data[url] . '" target="_blank">link' .  ++$link_count . '</a>');
				}
				
			}

			//Finish last record
			print('</div></td>');
			print('<td style="text-align: right;">' . number_format($price, 2) . '</td>');
			print('</tr>');

			print("</table>");
			print("<div class='wishlist-total-row'>" . number_format($total_estimate, 2) . "</div>");
			print("<div style='clear: both'></div>" );
			print("</div>" );

			

			//Next display items Purcahsed
			$query = "SELECT * FROM `wishlist` AS w WHERE w.status = 1";

			$result = mysql_query($query, $db->getLink() ) or die(mysql_error());
			
			print('<div class="wishlist-category-title">Captured Alive</div><div style="clear: both;"></div>');
			print("<div class='wishlist-table'><table border='0' cellspacing='1' cellpadding='0'>");
			$total_cost = 0;
			$total_estimate = 0;
			$total_net = 0;
			while($data = mysql_fetch_assoc($result) ){
				print("<tr>");
				

				print('<td style="padding-right: 10px;"><div class="wishlist-item-title">' . $data['title'] .  '<div style="color: black; margin-left: 14 px; font-size: 7pt; font-family: tahoma;">' . $data['purchased_comment'] . '</div></div></td>' );

				print("<td style='text-align: right;'>" . number_format($data['price'], 2) . "</td>");
				$total_estimate += $data['price'];

				print("<td style='text-align: right;'>" . number_format($data['purchased_price'], 2) . "</td>");
				$total_cost += $data['purchased_price'];

				print("<td style='text-align: right;'>" . number_format($data['purchased_price'] - $data['price'] , 2) . "</td>");
				$total_net += $data['purchased_price'] - $data['price'];

				print("</tr>");
			}
			print("</table>");
			print("<div class='wishlist-total-row'>" . number_format($total_cost, 2) . "</div>");
			print("<div style='clear: both'></div>" );
			print("</div>" );


			$db->close();

			
		
		}

		function resume(){
			print('On <a href="http://careers.stackoverflow.com/drdamour" target="_blank">StackOverflow</a>');
		}

		function aimstats(){
			require_once("aimstats.php");
			$aim = new aimstats($_GET['aimuser']);

			$aim->topvisits(25);

			if(isset($_GET['aimuser'])){
				print("<br/>");
				$aim->relative_visits();
				print("<br/>");
				$aim->history();	
			}

			print("<br/>");
			$aim->recent(25);


			print("<div align=center><form method=get><input type=hidden name='button' value='show' /><input type=hidden name='sub' value='aimstats' />anyuser: <input type=textbox name='aimuser' size=15 /> <input value='get info' type=submit></form></div>\n\n");

		}


	}
