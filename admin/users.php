<?php

/**
 * Panel administracyjny - Menu: U�ytkownicy
 *
 * @author Tolvat
 */

define('IN_ACP', true);

include("../core.php");
include("../config.php");

$Core     = new Core;
$template = $Core->HTML->Template;

if ($Core->GET->parse(array("search"), array(&$search)))
{
	if(empty($_GET['user'])) {
		echo "asd";
	} else {
		echo "<div id='search_results'>";

		$user = $_GET['user'];
		$sql = "SELECT login FROM users WHERE login LIKE '%$user%";
		$result = mysql_query($sql);

		while($row = mysql_fetch_assoc($result)) {
			$login = $row['login'];

			echo $login;
		}
	}
}else{

	// wczytaj szablon strony
	$template->load("acpUsers");
	$template->set("title", "Panel administratora: Użytkownicy");

	$template->set("blogUrl", $config['blog_url']);

	$top_bar = new Template(null);
	$top_bar->load("acpTopBar");

	if($Core->User === null) {
		$Core->HTML->redirect("../index.php", true);
	}

	// --------- TOP BAR
	$top_bar->set("user_id", $Core->User->getID());
	$top_bar->set("user_login", $Core->User->getLogin());

	// --------- TEMPLATE
	$template->set("user_login", $Core->User->getLogin());
	$template->set("acpTopBar", $top_bar->render_text());
	$template->set("vphp_version", $Core->VPHP->getVentureVersion());

	$template->set("blog_owner", $Core->VPHP->getBlogOwner());

	//   //this div is used to contain the results. Mostly used for styling.
	//      
	//   //This query searches the name field for whatever the input is.
	//   $sql = "SELECT link_text FROM Links WHERE link_tag_1 LIKE '%$searchq%' OR link_tag_2 LIKE '%$searchq%' OR link_tag_3 LIKE '%$searchq%' ";
	//      
	//   $result = mysql_query($sql);
	//   while($row = mysql_fetch_assoc($result)) {
	//    $id = $row['link_text'];
	//    echo "$id";
	//                 echo "br";
	//   }  
	//   echo "div";
	//   }

	$template->render();

}
unset($Core);
?>