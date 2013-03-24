<?php 

/*
 * Klasa zarz�dzaj�ca danym u�ytkownikiem
 */
class User
{
 private $SQL, $id, $login, $group;

 /**
  * Konstruktor
  * 
  * @param MySQL: wska�nik na klas� MySQL
  * @param int: ID u�ytkownika w bazie danych
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
  * Zwraca ID u�ytkownika
  */
 public function getID()
 {
  return $this->id;
 }
 
 /**
  * Zwraca login u�ytkownika
  */
 public function getLogin()
 {
  return $this->login; 
 }
 
 /**
  * Zwraca ID grupy do kt�rej nale�y u�ytkownik
  */
 public function getGroupID()
 {
  return $this->group;
 }
 
 /**
  * Zwraca nazw� grupy do kt�rej nale�y u�ytkownik
  */
 public function getGroupName()
 {
  return $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID()))->fetch_object()->name;
 }
 
 /**
  * Zwraca `true`, je�eli u�ytkownik ma prawo do pisania post�w
  */
 public function can_post()
 {
  return $this->SQL->query("SELECT * FROM groups WHERE id=%1", array($this->getGroupID()))->fetch_object()->can_post;
 }
 
 /**
  * Zwraca `true`, jeżeli użytkownik jest zalogowany / istnieje sesja logowania.
  * 
  * @author Tolvat
  */
 public function isLogged() {
 	return $this->logged;
 }
 
 /**
  * Zwraca `true`, jeżeli użytkownik jest administratorem.
  */
 public function isAdministrator() {
 	if($this->getGroupID() == 1) {
 		return true;
 	}
 	
 	return false;
 }
}
?>