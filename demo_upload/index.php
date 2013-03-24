<?php
 include("core.php");
 
 $Core = new Core;
 $Core->HTML->redirect("news.php"); // przekierowujemy go do `news.php`
 unset($Core);
?>