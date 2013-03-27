<?php
 // @TODO: paging

 include("core.php");

 $Core     = new Core;
 $template = $Core->HTML->Template;
 
 $post_template = new Template($Core->HTML);
 $post_list     = "";
 
 // wczytaj szablon strony
 $template->load("news")->
            set("title", "Najnowsze posty");
 
 // wczytaj szablon pojedynczego postu
 $post_template->load("post_preview");
 
 // pobierz posty
 $posts = $Core->SQL->query("SELECT * FROM posts ORDER BY ID DESC");
 
 if ($posts->num_rows === 0)
 {
  $post_list = "<div style='text-align: center; font-family: Helvetica'>Brak post�w na tej stronie.</div>";
 } else
 {
  // wyświetl posty
  while ($post = $posts->fetch_object())
  {
   $text = $post->content;
   $author = new User($Core->SQL, $post->author_id);
   
   if (strlen($text) > 1000) // tekst przekracza 250 znaków
    $text = substr($text, 0, 1000)." ...";
   
   if ($Core->User == null)
    $can_edit = false; else // niezalogowani nie mogą edytować postów...
   	$can_edit = ($author->getLogin() === $Core->User->getLogin()); // ...a użytkownicy mogą edytować posty, o ile są ich autorami
   
   $text = filter_var(htmlspecialchars($text, FILTER_SANITIZE_URL));
   
   $post_template->set("id", $post->id)->
                   set("title", $post->title)->
                   set("author", $author->getLogin())->
                   set("date", date("Y-m-d H:i", strtotime($post->date)))->
                   set("text", nl2br($text))->
                   set("edit_display", $can_edit?"inline":"none");
  	
   $post_list .= $post_template->render_text()."</br></br>";
   unset($author);
  }
 }
 
 if ($Core->User != null) // czy zalogowany?
  if ($Core->User->can_post()) // czy może pisać posty?
  {
   $post_list = "<div style='text-align: center'><a href='post.php?new' class='button'>Nowy post</a></div><br>".$post_list;
  } else
  if ($Core->User->can_add_users())
  {
   //dodawanie użytkowników zostanie dodane do ACP.
  }
 
 $template->set("post_list", $post_list)->render();
 
 unset($Core);
?>