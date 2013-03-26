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
  
  $user = $SQL->query("SELECT * FROM users WHERE id=%1", array($id))->fetch_object();
  
  $this->login = $user->login;
  $this->group = $user->group_id;
  
  // By Tolvat
  $this->logged = true;
  // By Tolvat
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
 public function getLogin()
 {
  return $this->login; 
 }
 
 /**
  * Zwraca ID grupy do ktï¿½rej naleï¿½y uï¿½ytkownik
  */
 public function getGroupID()
 {
  return $this->group;
 }
 
 /**
  * Zwraca nazwï¿½ grupy do ktï¿½rej naleï¿½y uï¿½ytkownik
  */
 public function getGroupName()
 {
  return $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID()))->fetch_object()->name;
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
}
?>