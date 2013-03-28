<?php

 /**
  * Panel administracyjny
  * 
  * @author Tolvat
  */

 define('IN_ACP', true);

 include("../core.php");
 include("../config.php");
 
 $Core     = new Core;
 $template = $Core->HTML->Template;
 
 // wczytaj szablon strony
 $template->load("acpIndex");
 $template->set("title", "Panel administratora");
 
 $template->set("blogUrl", $config['blog_url']);
 
 $top_bar = new Template(null);
 $top_bar->load("acpTopBar");
 
 if($Core->User === null || !$Core->User->isAdministrator()) {
 	$Core->HTML->redirect("../index.php", true);
 }
 
 if($Core->GET->parse(array("settings"), array(&$settings))) {
 	$template->load("acpSystemSettings");
 	$template->set("changeInfo", "");
 	
 	if($Core->POST->parse(array("descChange", "newDesc"), array(&$descChange, &$newDesc))) {
 		$Core->SQL->query("UPDATE settings SET value = '%1' WHERE name = 'vphpDesc'", array($newDesc));
 		$template->set("changeInfo", "<h2>Opis bloga zmieniony!</h2>");
 	}
 }
 
 if($Core->GET->parse(array("logo"), array(&$logo))) {
 	$template->load("acpSystemLogo");
 	$template->set("logo_url", $Core->SQL->query_str($Core->SQL->query("SELECT value FROM settings WHERE name = 'vphpLogoURL'"), "value"));
 	$template->set("logoChange", "");
 
 	if($Core->POST->parse(array("logoChangeBtn", "newLogoURL"), array(&$logoChangeBtn, &$newLogoURL))) {
 		if(empty($newLogoURL)) {
 			$template->set("logoChange", "<h2>Nie poda³eœ adresu to loga!</h2>");
 		}else{
 			$Core->SQL->query("UPDATE settings SET value = '%1' WHERE name = 'vphpLogoURL'", array($newLogoURL));
 			$template->set("logoChange", "<h2>Adres URL do logo zmieniony!</h2>");
 			
 			$template->set("logo_url", $Core->SQL->query_str($Core->SQL->query("SELECT value FROM settings WHERE name = 'vphpLogoURL'"), "value"));
 		}
 	}
 }
 
 // --------- TOP BAR
 $top_bar->set("user_id", $Core->User->getID());
 $top_bar->set("user_login", $Core->User->getLogin());
 
 // --------- TEMPLATE
 $template->set("user_login", $Core->User->getLogin());
 $template->set("acpTopBar", $top_bar->render_text());
 $template->set("vphp_version", $Core->VPHP->getVentureVersion());
 
 $template->set("blog_owner", $Core->VPHP->getBlogOwner());
 $template->set("blog_desc", $Core->SQL->query_str($Core->SQL->query("SELECT value FROM settings WHERE name = 'vphpDesc'"), "value"));
 
 $template->render();
 
 unset($Core);
?>