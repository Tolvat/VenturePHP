<?php

session_start();

include("user.php");
include("html.php");
include("misc.php");
include("mysql.php");

include("vphp.php");

/*
 * Klasa sï¿½uï¿½ï¿½ca ogï¿½lnemu opakowaniu wszystkiego
 */
class Core
{
 public $User, $HTML, $POST, $GET, $SQL, $VPHP;
 
 /**
  * Konstruktor
  */
 public function __construct($ConnectToDatabase=true)
 {
  include("config.php");
 	
  $this->User = null;
  $this->HTML = new HTML();
  $this->POST = new ArgumentParser($_POST);
  $this->GET  = new ArgumentParser($_GET);
  $this->SQL  = null;
  $this->VPHP = new VenturePHP();
  
  if ($ConnectToDatabase)
   $this->SQL = new MySQL($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
  
  if (isset($_SESSION["logged_in"])) // zalogowany
  {
   $this->User = new User($this->SQL, $_SESSION["user_id"]);
   
   // wyrenderuj pasek gï¿½rny
   $top_bar = new Template(null);
   $top_bar->load("top_bar_logged")->
             set("user_id", $this->User->getID())->
             set("user_login", $this->User->getLogin());
   
   $this->HTML->Template->set("top_bar", $top_bar->render_text());
   
   if($this->User->isAdministrator()) {
   	$this->HTML->Template->set("acpLink", "<a href='/admin/'>Panel administratora</a>");
   }else{
   	$this->HTML->Template->set("acpLink", "");
   }
   
   if($this->SQL->query_str($this->SQL->query("SELECT value FROM settings WHERE name = 'vphpLogoURL'"), "value") == "default") {
   	$this->HTML->Template->set("customLogo", "'". $config['blog_url'] . "/images/logo.png'");
   }else{
   	$this->HTML->Template->set("customLogo", "'" . $this->SQL->query_str($this->SQL->query("SELECT value FROM settings WHERE name = 'vphpLogoURL'"), "value") . "'");
   }
   
   unset($top_bar);
  }
 }
 
 /**
  * Destruktor
  */
 public function __destruct()
 {
  unset($this->User);
  unset($this->HTML);
  unset($this->POST);
  unset($this->GET);
  unset($this->SQL);
  unset($this->VPHP);
 }
 
 /**
  * Ta funkcja sprawdza, czy u¿ytkownik o takiej nazwie ju¿ istnieje.
  * @param unknown $Username
  */
 public function isCreated($Username) {
 	if($this->SQL->query("SELECT login FROM users WHERE login = '$Username'") == $Username) {
 		return true;
 	}
 	
 	return false;
 }
}

?>