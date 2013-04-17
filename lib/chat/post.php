<?php
session_start();  
if(isset($_SESSION['username'])){  
    $text = $_POST['message'];  
 
    $fp = fopen("log.html", 'a');  
    fwrite($fp, "<div class='msgln'>&nbsp;<b>".$_SESSION['user_info']['firstname']."</b><div class='msgln_time'>".date("g:i A")."&nbsp;</div><br/>&nbsp;".stripslashes(htmlspecialchars($text))."</div>");  
    fclose($fp);  
}  
?>
