<?php
 include("core.php");
 
 $Core     = new Core;
 $template = $Core->HTML->Template;
 
 $template->load("login");
 $template->set("title", "Logowanie");
 
 if ($Core->GET->parse(array("logout"), array(&$logout)))
 {
  session_destroy();
  $Core->HTML->redirect("index.php", true);
 }
 
 if($Core->GET->parse(array("createacc"), array(&$createacc)) && $Core->User == null) {
  session_destroy();
  $template->load("createAcc");
  $template->set("createAcc", "Stwórz nowe konto");
 }
 
 if ($Core->User != null) // już zalogowany
  $Core->HTML->redirect("index.php", true);
 
 $login_failed = false;
 $register_failed = 0;
 
 if ($Core->POST->parse(array("username", "password"), array(&$username, &$password))) // próba logowania
 {
  $password = sha1(md5($password));
  $query = $Core->SQL->query("SELECT * FROM users WHERE login='%1' AND password='%2'", array($username, $password));
  
  if ($query->num_rows === 0)
  {
   $login_failed = true; // logowanie nie powiodło się
  } else
  { // logowanie udane
   $User = new User($Core->SQL, $query->fetch_object()->id);
   
   $_SESSION["logged_in"] = true;
   $_SESSION["user_id"]   = $User->getID();
   
   $Core->HTML->redirect("index.php", true);
  }
 }
 
 if ($login_failed)
 {
  $template->set("login_failed", "Logowanie nieudane!</br></br>");
  $template->set("login_failed_box_height", 30);
 } else
 {
  $template->set("login_failed", "");
  $template->set("login_failed_box_height", 0);
 }

 if ($register_failed == 1) {
 	$template->set("register_failed", "Rejestracja nie powiodła się!<br /><br />");
 	$template->set("register_failed_box_height", 30);
 } else if($register_failed == 2) {
 	$template->set("register_failed", "Rejestracja przebiegła pomyślne!<br /><a href='login.php'>Kliknij, tutaj aby przejść do strony logowania.</a><br /><br />");
 	$template->set("register_failed_box_height", 40);
 } else {
 	$template->set("register_failed", "");
 	$template->set("register_failed_box_height", 0);
 }
 
 
 $template->render();
 unset($Core);
?>