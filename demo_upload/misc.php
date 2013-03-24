<?php

/*
 * Klasa s³u¿¹ca parsowaniu wejœciowych danych GET oraz POST
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
  * Parsuje oraz "zabezpiecza" dane z tablicy (tak, by mog³y zostaæ bezpoœednio dodane do SQL) oraz zwraca zwalidowane wartoœci przez argumenty funkcji
  * 
  * @param string array: nazwy argumentów do przeparsowania
  * @param variable pointers: do tych zmiennych zostan¹ kolejno wczytane te parametry
  *
  * @return: zwraca `true` gdy wszystkie parametry zosta³y wczytane lub `false` w przeciwnym wypadku.
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