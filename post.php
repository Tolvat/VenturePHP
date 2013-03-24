<?php
 include("core.php");

 $Core     = new Core;
 $template = $Core->HTML->Template;
 $content  = "";
 
 // wczytaj szablon strony
 $template->load("post", array("post", "news"));
 
 // czy wyświetlić posta?
 if ($Core->GET->parse(array("view"), array(&$view)))
  $view = intval($view); else
  $view = null;
 
 // czy stworzyć posta?
 if ($Core->GET->parse(array("new"), array(&$new)))
  $new = true; else
  $new = null;
 
 // czy zapisać już napisanego posta?
 if ($Core->POST->parse(array("sent", "title", "content"), array(&$sent, &$title, &$content)))
  $sent = true; else
  $sent = null;
 
 // czy usunąć posta?
 if (!$Core->GET->parse(array("remove"), array(&$remove)))
  $remove = null;
 
 // czy edytować posta?
 if (!$Core->GET->parse(array("edit"), array(&$edit)))
  $edit = null;
 
 // czy zapisać już wyedytowanego posta?
 if ($edit == null)
  if (!$Core->POST->parse(array("edit", "title", "content"), array(&$edit, &$title, &$content)))
   $edit = null;
 
 /** wyświetlanie posta */
 if ($view != null)
 {
  // wczytaj szablon wyświetlania posta
  $post_template = new Template($Core->HTML);
  $post_template->load("post_preview");
  
  // pobierz posta
  $post = $Core->SQL->query("SELECT * FROM posts WHERE id=%1", array($view));
  
  if ($post->num_rows == 0) // błąd: taki post nie istnieje
   $Core->HTML->redirect("index.php", true);
  	
  $post = $post->fetch_object();
  
  // wyświetl posta
  $text = $post->content;
  $author = new User($Core->SQL, $post->author_id);
   
  if ($Core->User == null)
   $can_edit = false; else // niezalogowani nie mogą edytować postów...
   $can_edit = ($author->getLogin() === $Core->User->getLogin()); // ...a użytkownicy mogą edytować posty, o ile są ich autorami
   
  $post_template->set("id", $post->id)->
                  set("title", $post->title)->
                  set("author", $author->getLogin())->
                  set("date", date("Y-m-d H:i", strtotime($post->date)))->
                  set("text", nl2br($text))->
                  set("edit_display", $can_edit?"inline":"none");
   
  $content .= $post_template->render_text();
  $template->set("title", "Post użytkownika ".$author->getLogin());
 } else
 
 /** tworzenie nowego posta */
 if ($new != null)
 {
  if ($Core->User == null) // użytkownik niezalogowany
   $Core->HTML->redirect("index.php", true);
  
  if (!$Core->User->can_post()) // użytkownik nie ma praw do pisania postów
   $Core->HTML->redirect("index.php", true);
 	
  // wczytaj szablon tworzenia posta
  $post_template = new Template($Core->HTML);
  $post_template->load("new_post")->
                  set("title", "")->
                  set("content", "")->
                  set("hidden_name", "sent")->
                  set("hidden_value", "1");
  
  $content .= $post_template->render_text();
  $template->set("title", "Nowy post");
 } else
 	
 /** zapisywanie już utworzonego posta */
 if ($sent != null)
 {
  if ($Core->User == null) // użytkownik niezalogowany
   $Core->HTML->redirect("index.php", true);
 	
  if (!$Core->User->can_post()) // użytkownik nie ma praw do pisania postów
   $Core->HTML->redirect("index.php", true);
  
  $title = empty($title) ? "Niepodano tytułu." : $title;
  $content = str_replace(array("\n", "\r\n"), "</br>", $content); // znaki nowej linii zamieniamy na `</br>`
  $Core->SQL->query("INSERT INTO posts VALUES(0, \"%1\", \"%2\", NOW(), %3)", array($title, nl2br($content), $Core->User->getID()));
  $Core->HTML->redirect("index.php", true);
  
 } else
 	
 /** usuwanie posta */
 if ($remove != null)
 {
  // pobierz posta
  $post = $Core->SQL->query("SELECT * FROM posts WHERE id=%1", array($remove));
 	
  if ($post->num_rows == 0) // błąd: taki post nie istnieje
   $Core->HTML->redirect("index.php", true);

  // przeparsuj posta
  $post = $post->fetch_object();
 	
  $author = new User($Core->SQL, $post->author_id);
  
  if ($Core->User == null)
   $can_remove = false; else // niezalogowani nie mogą edytować postów...
   $can_remove = ($author->getLogin() === $Core->User->getLogin()); // ...a użytkownicy mogą edytować posty, o ile są ich autorami
  
  if ($can_remove)
   $Core->SQL->query("DELETE FROM posts WHERE id=%1", array($remove));
  
  $Core->HTML->redirect("index.php", true);
 } else
 	
 /** edycja posta */
 if ($edit != null)
 {
  // pobierz posta
  $post = $Core->SQL->query("SELECT * FROM posts WHERE id=%1", array($edit));
 	
  if ($post->num_rows == 0) // błąd: taki post nie istnieje
   $Core->HTML->redirect("index.php", true);
 	
  // przeparsuj posta
  $post = $post->fetch_object();
 	
  $author = new User($Core->SQL, $post->author_id);
 	
  if ($Core->User == null)
   $can_edit = false; else // niezalogowani nie mogą edytować postów...
   $can_edit = ($author->getLogin() === $Core->User->getLogin()); // ...a użytkownicy mogą edytować posty, o ile są ich autorami
  
  if (!$can_edit) // błąd: użytkownik nie ma praw do edycji tego postu
   $Core->HTML->redirect("index.php", true);
  
  /** czy post został już zedytowany? */
  if (isset($title) && isset($content))
  {
   $post_content = str_replace(array("\n", "\r\n"), "</br>", $content); // znaki nowej linii zamieniamy na `</br>`

   $Core->SQL->query("UPDATE posts SET title=\"%1\", content=\"%2\" WHERE id=%3", array($title, $post_content, $edit));
   $Core->HTML->redirect("post.php?view=".$edit, true);
  } else
  {
   $post_content = str_replace(array("<br>", "</br>"), "\n", $post->content); // `</br>` oraz `<br>` zamieniamy na znak nowej linii
   
   // wczytaj szablon tworzenia posta, który posłuży nam za szablon edycji
   $post_template = new Template($Core->HTML);
   $post_template->load("new_post")->
                   set("title", $post->title)->
                   set("content", $post_content)->
                   set("hidden_name", "edit")->
                   set("hidden_value", $edit);
   
   $content .= $post_template->render_text();
   $template->set("title", "Edycja postu");
  }
 }else{
 	// jeśli nie ma podanego żadnego parametru (w adresie jest samo "post.php" - przekieruj do dodawania nowego posta)
 	$Core->HTML->redirect("post.php?new", true);
 }
 
 $template->set("post_list", $content)->render();
 
 unset($Core);
?>