<?php

/**
 * Venture PHP Global Class (Second Core)
 * 
 * @author Tolvat
 */

include("config.php");

class VenturePHP {
	/**
	 * Instance
	 */
	public $instance;
	
	/**
	 * 
	 */
	public $HTML, $POST, $GET, $SQL, $BBCode;
	
	/**
	 * Zmienne przechowujÄ…ce informacje nt. VenturePHP
	 */
	public $vphpVersion = '1.1.1';
	public $pluginList;
	public $devMode = 1; // 1 jeœli w³¹czony, 0 jeœli wy³¹czony

	/**
	 * Konstruktor
	 */
	public function __construct() {
		include("config.php");
		
  		$this->HTML = new HTML();
  		$this->POST = new ArgumentParser($_POST);
  		$this->GET  = new ArgumentParser($_GET);
  		$this->SQL  = new MySQL($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
	}
	
	/**
	 * Zwraca tablicê z nazwami oraz g³ównymi plikami ka¹dego z pluginów.
	 */
	public function getPlugins() {
		return $this->pluginList;
	}
	
	/**
	 * Zmienia w³aœciciela bloga (g³ównego administratora)
	 */
	public function setVentureOwner($newOwner) {
		if(empty($newOwner)) {
			return;
		}
		
		$this->SQL->query("UPDATE settings SET value = '$newOwner' WHERE name = 'vphpOwner'");
	}
	
	/**
	 * Zwraca wersjê VenturePHP.
	 */
	public function getVentureVersion() {
		return $this->vphpVersion;
	}
	
	/**
	 * Zwraca nazwê g³ównego administratora bloga.
	 */
	public function getBlogOwner() {
		return $this->SQL->query_str($this->SQL->query("SELECT value FROM settings WHERE name = 'vphpOwner'"), "value");
	}
}

/**
 * @TODO ca³a ta klasa
 * @author Tolvat
 *
 */
class vphp_core_bbcode {
	/**
	 * BBCodes
	 */
	public $bbcodes = array();
	
	public $Core;
	
	public function __construct() {
		$this->addBBCode("bold", "[b}", "[/b]", "<strong>", "</strong>");
		//$this->addBBCode("italic", "[i}", "[/i]");
		$this->addBBCode("image", "[img}", "[/img]", "<img src='", "'>");
		
		$this->Core = new Core();
	}
	
	/**
	 * Add new BBCode
	 * 
	 * ex. name = bold
	 * ex. value = [b]
	 * ex. valueEnd = [/b]
	 */
	public function addBBCode($name, $value, $valueEnd, $valueReplace, $valueReplaceEnd) {
		$this->bbcodes[$name] = $value;
		$this->bbcodes[$name . ".end"] = $valueEnd;
		$this->bbcodes[$name . ".vrep"] = $valueReplace;
		$this->bbcodes[$name . ".vrepEnd"] = $valueReplaceEnd;
	}
	
	public function getBBCode($name, $type) {
		switch($type) {
			case '1':
				return $this->bbcodes[$name];
				break;
			case '2':
				return $this->bbcodes[$name . ".end"];
				break;
			case '3':
				return $this->bbcodes[$name . ".vrep"];
				break;
			case '4':
				return $this->bbcodes[$name . ".vrepEnd"];
			default:
				break;
		}
	}
}

/**
 * @TODO Ca³a ta klasa
 * @author Tolvat
 *
 */
class vphp_core_theme {
	/**
	 * Used theme
	 */
	public $theme;
	
	/**
	 * Informacje o u¿ywanym aktualnie skinie
	 */
	public $theme_info = array();
	
	public function __construct() {
		$this->theme = $this->getUsedTheme();
	}
	
	public function getUsedTheme() {
		if($theme == null) {
			return "default";
		}
		
		return $theme;
	}
	
	public function setUsedTheme($to, $arrayWithThemeInfo) {
		$this->theme = $to;
		$this->theme_info = $arrayWithThemeInfo;
	} 
}