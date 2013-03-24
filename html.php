<?php

/*
 * Klasa odpowiedzialna za obs�ug� szablon�w strony.
 */
class Template
{
 public $HTML;
 
 private $code;
 private $vars;
 
 /**
  * Konstruktor
  * 
  * @param HTML: wska�nik na klas� `HTML`
  */
 public function __construct($HTML)
 {
 if(defined('IN_ACP')) {
  	include("../config.php");
  }else{
  	include("config.php");
  }
  
  $this->HTML = $HTML;
  $this->vars = array(""=>"");
  
  $this->set("stylesheets", "<link rel='stylesheet' type='text/css' href='global.css'/>");
  $this->set("top_bar", file_get_contents($config['blog_url'] . "templates/top_bar.html"));
  
  return $this;
 }
 
 /**
  * Wczytuje szablon z pliku
  * 
  * @param string: nazwa pliku szablonu. BEZ nazwy katalogu `templates` oraz BEZ rozszerzenia! Np.po prostu `login`, a *nie* `templates/login` czy `login.html`
  * @param string: nazwa pliku CSS szablonu, kryteria podobne jak wy�ej. Mo�e zosta� r�wnie� przekazane jako tablica string-�w.
  * 
  * @note: je�eli `$cssfile === null`, nast�puje pr�ba automatycznego wczytania CSS danego template-u na podstawie nazwy pliku
  */
 public function load($file, $cssfile=null)
 {
  if(defined('IN_ACP')) {
  	include("../config.php");
  }else{
  	include("config.php");
  }
 
  if ($cssfile === NULL)
   $cssfile = $file;
 	
  $file = $config['blog_url'] . "templates/".$file;	
  
  $this->code = file_get_contents($file.".html");
  
  if (!is_array($cssfile))
   $cssfile = Array($cssfile);
  
  foreach ($cssfile as $css)
  {
   $css = "templates/".$css.".css";
   if (file_exists($css))
    $this->append("stylesheets", "<link rel='stylesheet' type='text/css' href='".$css."'/>");
  }
  
  $this->append("stylesheets", "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>"); // @note: to jest z�e :F (niejednoznaczno�� nazwy zmiennej)
  
  return $this;
 }
 
 /**
  * Ustawia zmienn� szablonow� na dan� warto��
  * 
  * @param string: nazwa zmiennej
  * @param string: warto��
  */
 public function set($name, $value)
 {
  $this->vars[$name] = $value;
  return $this;
 }
 
 /**
  * Dopisuje warto�� do zmiennej szablonowej (na jej koniec)
  * 
  * @param string: nazwa zmiennej
  * @param string: warto�� do dopisania
  */
 public function append($name, $value)
 {
  $this->vars[$name] .= $value;
  return $this;
 }
 
 /**
  * Renderuje szablon i zwraca go poprzez wynik
  * 
  * @return string: wyrenderowany szablon
  */
 public function render_text()
 {
  $code = $this->code;
  
  foreach ($this->vars as $name=>$value)
   $code = str_replace('${'.$name.'}', $value, $code);
  
  return $code;
 }
 
 /**
  * Renderuje szablon i wy�wietla go na wyj�ciu HTML
  */
 public function render()
 {
  $this->HTML->write($this->render_text());
  return $this;
 }
}

/*
 * Klasa odpowiedzialna za wysy�anie kodu do u�ytkownika.
 */
class HTML
{
 public $Template;
	
 /**
  * Konstruktor
  */
 public function __construct()
 {
  ob_start();
  
  $this->Template = new Template($this);
 }
 
 /**
  * Destruktor
  * 
  * Wysy�a dane zapisane w buforze na wyj�cie oraz niszczy obiekt
  */
 public function __destruct()
 {
  $this->flush();
 }
	
 /**
  * Wysy�a dane do bufora.
  * 
  * @param string: tekst do wy�wietlenia
  */
 public function write($text)
 {
  echo $text;
 }
 
 /**
  * Wysy�a dane w buforze na wyj�cie
  */ 
 public function flush()
 {
  ob_flush();
 }
 
 /**
  * Przekierowuje u�ytkownika na dan� stron�
  * 
  * @param string: strona do kt�rej ma nast�pi� przekierowanie.
  * @param bool: je�eli ustawiony na `true`, funkcja po wys�aniu nag��wka przekierowania zatrzymuje parser PHP.
  *
  * @note: funkcja nie mo�e by� u�yta, je�eli *wys�ane* zosta�y ju� jakie� nag��wki b�d� tekst.
  */
 public function redirect($page, $stop_executing=false)
 {
  Header("Location: ".$page);
  
  if ($stop_executing)
   exit;
 }
}

?>