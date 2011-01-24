<?   // Copyright (c) Isaac Gouy 2011 ?>

<?
   list($score,$labels,$stats,$selected) = $Data;
   unset($Data);  
   unset($labels);
   unset($selected);
?>

<p>Which programming language implementations have the fastest benchmark programs?</p>
<p>Median x86 Ubuntu (normalized) elapsed time measurements:</p>
<ul><?
foreach($score as $k => $v){
   $Name = $Langs[$k][LANG_FULL];
   $HtmlName = $Langs[$k][LANG_HTML];
   printf('<li>%0.2f %s</li>', $v[STAT_MEDIAN], $HtmlName); 
   echo "\n";
}
?>
</ul>




