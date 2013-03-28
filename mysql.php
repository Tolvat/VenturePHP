<?php 

/*
 * Klasa s�u��ca zarz�dzaniu baz� danych
 */
class MySQL
{
 private $mysqli;
 	
 /**
  * Konstruktor
  */
 public function __construct($host, $user, $pass, $db_name)
 {
   $this->mysqli = new mysqli($host, $user, $pass, $db_name);
  
   if ($this->mysqli->connect_errno)
   {
    ob_clean();
    die("Couldn't connect to MySQL server: ".$mysqli->connect_error);
   }

 	mysql_connect($host, $user, $pass);
 	mysql_select_db($db_name);
 	$this->mysqli->set_charset("utf8");
 }
 
 /**
  * Destruktor
  */
 public function __destruct()
 {
  $this->mysqli->close();
 }
 
 /**
  * Wykonuje zapytanie MySQL
  * 
  * @param string: zapytanie do wykonania
  * 
  * @return zwraca wynik zapytania mysqli
  */
 public function query($query, $params=null)
 {
  if ($params != null)
  {
   foreach ($params as $id=>$value)
   	$query = str_replace("%".(intval($id)+1), $value, $query);
  }

  if (!$result = $this->mysqli->query($query))
  {
   ob_clean();
   echo "SQL query failed:</br>".
        $query."</br></br>".
        $this->mysqli->error;
   die("");
  }
  
  return $result;
 }
 
 /**
  * Wykonuje zapytanie MySQL
  * 
  * @param string: zapytanie do wykonania
  * 
  * @return zwraca "sam siebie" (`this`)
  */
 public function query_t($query)
 {
  if (!$this->mysqli->query($query))
  {
   ob_clean();
   echo "SQL query failed:</br>".
 		$query."</br></br>".
 		$this->mysqli->error;
   die("");
  }
 
  return $this;
 }
 
 /**
  * Wczytuje dany plik i wykonuje zapytania w nim zawarte.
  * Ka�de zapytanie musi by� oddzielone �rednikiem `;`
  * 
  * @param string: nazwa pliku
  */
 public function query_from_file($file)
 {
  $queries = explode(";", file_get_contents($file));
  foreach ($queries as $query)
   $this->query($query);
 }
 
 /**
  * Wykonuje zapytanie MySQL,
  * Zaleca si� u�ywanie go, gdy pobieramy co� z bazy danych (np. jaki� tekst)
  * 
  * Zrobi�em t� funkcj�, aby u�atwi� sobie programowanie, oraz u�atwi� innym zrozumienie kodu.
  * 
  * @param string $query: zapytanie
  * @param string $return: co ma zwr�ci�?
  * @return anything
  */
 public function query_str($query, $return) {
 	while($returnValues = $query->fetch_object()) {
 		$returnValue = $returnValues->$return;
 		
 		return $returnValue;
 	}
 }
}

?>