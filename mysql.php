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
  * Wczytuje ��dany plik i wykonuje zapytania w nim zawarte.
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
 
 public function query_select($query)
 {
 	IF(!$this->error)
 	{
 		IF(!$result = $this->mysqli->query($query))
 		{
 			$this->error = true;
 			throw new Exception('Błąd wykonania zapytania - ('.$query.') - '.$this->mysqli->error, $this->mysqli->errno);
 		}
 		while($row = $result->fetch_assoc())
 		{
 			$return[] = $row;
 		}
 		return $return[0];
 	}
 }
}

?>