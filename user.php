<?php 

/*
 * Klasa zarz¹dzaj¹ca danym u¿ytkownikiem.
 */
class User
{
 private $SQL, $id, $login, $group;

 /**
  * Konstruktor
  * 
  * @param MySQL: wkaŸnik na klasê MySQL
  * @param int: ID u¿ytkownika z bazy danych
  */
 public function __construct($SQL, $id)
 {
  $this->SQL = $SQL;
  $this->id  = $id;
  
  $user = $this->SQL->query("SELECT * FROM users WHERE id=%1", array($id))->fetch_object();
  
  $this->login = $user->login;
  $this->group = $user->group_id;
  
  $this->logged = true;
  $this->posts = $user->posts;
 }
 
 /**
  * Zwraca ID u¿ytkowników
  */
 public function getID()
 {
  return $this->id;
 }
 
 /**
  * Zwraca login u¿ytkownika
  */
 public function getLogin($Username=null)
 {
 	if($Username == null) {
  		return $this->login;
 	}
 }
 
 /**
  * Zwraca ID grupy do ktï¿½rej naleï¿½y uï¿½ytkownik
  */
 public function getGroupID($Username=null)
 {
  if($Username == null) {
  	return $this->group;
  } else {
  	return $this->SQL->query_str($this->SQL->query("SELECT group_id FROM users WHERE login = '%1'", array($Username)), "group_id");
  }
 }
 
 /**
  * Zwraca nazwï¿½ grupy do ktï¿½rej naleï¿½y uï¿½ytkownik
  */
 public function getGroupName($Username=null)
 {
 	if($Username == null) {
  		return $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID()))->fetch_object()->name;
 	} else {
 		$Query = $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID($Username)))->fetch_object()->name;
 		
 		if($Query == null) {
 			return "<strong>TAKA GRUPA NIE ISTNIEJE!</strong>";
 		}else{
 			return $Query;
 		}
 	}
 }
 
 /**
  * Zwraca `true`, jeï¿½eli uï¿½ytkownik ma prawo do pisania postï¿½w
  */
 public function can_post()
 {
  return $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID()))->fetch_object()->can_post;
 }
 
 public function can_add_users() {
 	return false;
 }
 
 /**
  * Zwraca `true`, jeÅ¼eli uÅ¼ytkownik jest zalogowany / istnieje sesja logowania.
  * 
  * @author Tolvat
  */
 public function isLogged() {
 	return $this->logged;
 }
 
 /**
  * Zwraca `true`, jeÅ¼eli uÅ¼ytkownik jest administratorem.
  */
 public function isAdministrator() {
 	if($this->getGroupID() == 1) {
 		return true;
 	}
 	
 	return false;
 }
 
 /**
  * Zwraca liczbê postów napisanych przez u¿ytkownika.
  * Pozostaw $Username puste, jeœli chcesz pobraæ dane o zalogowaynm u¿ytkowniku.
  */
 public function getPostCount($Username=null) {
 	if($Username == null) {
 		return $this->posts;
 	}else{
 		return $Query = $this->SQL->query("SELECT posts FROM users WHERE login='%1'", array($Username))->fetch_object()->posts;
 	}
 }
}
?>