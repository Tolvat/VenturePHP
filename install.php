<?php 
 include("core.php");
 include("config.php");
 
 session_destroy();

 $Core = new Core(false);
 $Core->SQL = new MySQL($config["db_host"], $config["db_user"], $config["db_pass"], null);

 $Core->SQL->query_t("DROP DATABASE IF EXISTS ".$config["db_name"])->
             query_t("CREATE DATABASE ".$config["db_name"])->
             query_t("USE ".$config["db_name"])->
             query_from_file("install.sql");

 $Core->HTML->write("Instalacja zakoñczona! :)");
 
 unset($Core);
?>