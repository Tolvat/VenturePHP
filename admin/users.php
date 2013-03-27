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
		echo "";
	} else {
		$User = $_GET['user'];
		$User = mysql_real_escape_string($User);
		
		$Sql = $Core->SQL->query("SELECT * FROM users WHERE login = '%1'", array($User));
		
		if($Sql->num_rows == 0) {
			$Core->HTML->write("Nie odnaleziono użytkownika - <strong>" . $User . "</strong>");
		}else{
			$Core->HTML->write("Znaleziono użytkownika! (<strong>" . $User . "</strong>) <br /><br />");
			// Pokaż informacje o użytkowniku
			$Core->HTML->write("Grupa użytkownika: <strong>" . $Core->User->getGroupName($User) . "</strong> (ID grupy: " . $Core->User->getGroupID($User) . ")<br />");
			$Core->HTML->write("Ilość napisanych postów: <strong>" . $Core->User->getPostCount($User) . "</strong><br />");
		}
	}
}else{

	// wczytaj szablon strony
	$template->load("acpUsers");
	$template->set("title", "Panel administratora: Użytkownicy");

	$template->set("blogUrl", $config['blog_url']);

	$top_bar = new Template(null);
	$top_bar->load("acpTopBar");

	if($Core->User === null || !$Core->User->isAdministrator()) {
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