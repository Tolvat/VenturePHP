<?php
include("core.php");

$Core = new Core;

if($Core->User == null) {
	$Core->HTML->redirect("index.php", true);
}else{
	session_destroy();
	$Core->HTML->redirect("index.php", true);
}
