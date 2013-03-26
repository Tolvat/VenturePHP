<?php
 /**
  * Rejestracja użytkownik�w
  * @author Tolvat
  */

 include("core.php");

 $Core     = new Core;
 $template = $Core->HTML->Template;
 
 // wczytaj szablon strony
 $template->load("createacc")->
            set("title", "Tworzenie nowego konta");
 
 if(isset($_POST['create'])) {
 	$Username = $_POST['username'];
 	$Password_Hash = sha1(md5($_POST['password']));
 	$Password_Normal = $_POST['password'];
 	
 	/**
	 * Zapytanie sprawdzające czy użytkownik z taką nazwą już istnieje (jeśli tak - zwróci jego nazwę)
 	 */
 	$sqlQuery = $Core->SQL->query_str($Core->SQL->query("SELECT login FROM users WHERE login = '$Username'"), "login");
 	
 	if($sqlQuery == $Username) {
 		$template->set("register_failed", "Użytkownik o takiej nazwie już istnieje!");
 	}else{
 		$Core->SQL->query("INSERT INTO users VALUES(0, '%1', '%2', 2)", array($Username, $Password_Hash));
 		$template->set("register_failed", "Rejestracja przebiegła pomyślnie!<br /><a href='index.php'>Kliknij, aby przejść do strony logowania</a>");
 		
 	}
 	
 	
 }else{
 	$template->set("register_failed", "");
 }
 
 $template->render();
 
 unset($Core);
?>