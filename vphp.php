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
	 * Zmienne przechowujące informacje nt. VenturePHP
	 */
	public $vphpVersion = '1.1.1';
	public $pluginList;
	public $devMode = 1; // 1 je�li w��czony, 0 je�li wy��czony

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
	 * Zwraca tablic� z nazwami oraz g��wnymi plikami ka�dego z plugin�w.
	 */
	public function getPlugins() {
		return $this->pluginList;
	}
	
	/**
	 * Zmienia w�a�ciciela bloga (g��wnego administratora)
	 */
	public function setVentureOwner($newOwner) {
		if(empty($newOwner)) {
			return;
		}
		
		$this->SQL->query("UPDATE settings SET value = '$newOwner' WHERE name = 'vphpOwner'");
	}
	
	/**
	 * Zwraca wersj� VenturePHP.
	 */
	public function getVentureVersion() {
		return $this->vphpVersion;
	}
	
	/**
	 * Zwraca nazw� g��wnego administratora bloga.
	 */
	public function getBlogOwner() {
		return $this->SQL->query_str($this->SQL->query("SELECT value FROM settings WHERE name = 'vphpOwner'"), "value");
	}
}

/**
 * @TODO ca�a ta klasa
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
 * @TODO Ca�a ta klasa
 * @author Tolvat
 *
 */
class vphp_core_theme {
	/**
	 * Used theme
	 */
	public $theme;
	
	/**
	 * Informacje o u�ywanym aktualnie skinie
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