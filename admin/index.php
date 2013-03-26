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
 
 $template->render();
 
 unset($Core);
?>