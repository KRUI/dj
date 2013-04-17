<?php
//PHP-CLI Test
///ping
//$command = 'ping -c 4  google.com';
///top [linux batch mode]
//$command = 'top -b -n 1';
$command = 'say -v Zarvox Hey Nate! Voiceover capability for K-R-U-I D-J?';
$exec = exec ($command, $output, $return_var);
echo ('<pre>');
print_r ($output);
echo('</pre>');
echo ('<pre>');
print_r ($return_var);
echo('</pre>');
exit();
?>