<?php

/*
 * Klasa s�u��ca parsowaniu wej�ciowych danych GET oraz POST
 */
class ArgumentParser
{
 private $data;
 
 /**
  * Konstruktor
  * 
  * @param string array: odpowiednio $_GET, $_POST lub dowolna inna tablica asocjacyjna 
  */
 public function __construct($data)
 {
  $this->data = $data;
 }
 
 /**
  * Parsuje oraz "zabezpiecza" dane z tablicy (tak, by mog�y zosta� bezpo�ednio dodane do SQL) oraz zwraca zwalidowane warto�ci przez argumenty funkcji
  * 
  * @param string array: nazwy argument�w do przeparsowania
  * @param variable pointers: do tych zmiennych zostan� kolejno wczytane te parametry
  *
  * @return: zwraca `true` gdy wszystkie parametry zosta�y wczytane lub `false` w przeciwnym wypadku.
  */
 public function parse($names, $args)
 {
  for ($i=0; $i<count($names); $i++)
  {
   if (!isset($this->data[$names[$i]])) // nie odnaleziono parametru
    return false;
   
   $args[$i] = mysql_real_escape_string($this->data[$names[$i]]);
  }
   
  return true;
 }
}
?>