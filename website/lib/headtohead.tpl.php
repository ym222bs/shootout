<?   // Copyright (c) Isaac Gouy 2004, 2005 ?>

<div>
<? 
$Row = $Langs[$SelectedLang];
$LangName = $Row[LANG_FULL];
$LangTag = $Row[LANG_TAG];
$LangName2 = $Langs[$SelectedLang2][LANG_FULL];
$Link = $Row[LANG_LINK];
$Family = $Row[LANG_FAMILY];
$ShortName = $Row[LANG_NAME];
$ShortName2 = $Langs[$SelectedLang2][LANG_NAME];
?>

<p>Compare the performance of <strong><?=$LangName;?></strong> programs against some other language implementation, or check the <?=$Family;?> <a href="benchmark.php?test=all&amp;lang=<?=$Link;?>&amp;lang2=<?=$Link;?>" title="Show <?=$LangName;?> measurements">CPU time and Memory use measurements</a>.</p>

<p>For more information about the <?=$Family;?> implementation we measured see
<a href="#about" title="Read about the <?=$LangName;?> language implementation">about <?=$LangName;?></a>.</p>
</div>

<!-- // MENU /////////////////////////////////////////////////// -->

<div>
<? MkHeadToHeadMenuForm($Tests,$SelectedTest,$Langs,$SelectedLang,$SelectedLang2,"fullcpu"); ?>
</div>

<!-- // TAG /////////////////////////////////////////////////// -->

<table class="div">
<tr><td>
<h4 class="rev"><a class="arev" href="#title" name="title">&nbsp;Are the <?=$ShortName;?> programs better?</a></h4>
<p>For each of one our benchmarks, a white chart bar shows which language implementation had the faster program, and a black chart bar shows which used the least memory.</p>
</td></tr>



<!-- // CHART //////////////////////////////////////////////////// -->

<tr><td class="center">
<img src="chartvs.php?test=<?=$SelectedTest;?>&amp;lang=<?=$SelectedLang;?>&amp;lang2=<?=$SelectedLang2;?>"
   alt="Are the <?=$LangName;?> programs better or are the the <?=$LangName2;?> programs better?"
   title="Are the <?=$LangName;?> programs better or are the the <?=$LangName2;?> programs better?"
   width="300" height="300"
 />
</td></tr>

<tr><td>&nbsp;</td></tr>


<!-- // TABLE ////////////////////////////////////////////// -->


<tr><td>
<h4 class="rev"><a class="arev" href="#ratio" name="ratio">&nbsp;How many times better?</a></h4>
<p>How many times <em>faster or smaller</em> are the <strong><?=$LangName;?></strong> programs than the corresponding <?=$LangName2;?> programs?</p>

</td></tr>


<tr><td><table>
<tr>
<th class="c">&nbsp;</th>
<th>&nbsp;Faster&nbsp;</th>
<th>Smaller: Memory Use</th>
<th>Smaller: Code Lines</th>
<th class="c">&nbsp;</th>
</tr>

<tr>
<th>Program &amp; Logs</th>
<th colspan="3"><?=$ShortName;?> <em>x&nbsp;times</em>&nbsp;better <span class="i"><br/><?=$ShortName2;?> <em>x&nbsp;times</em>&nbsp;better</span></th>
<th>&nbsp;N&nbsp;</th>
</tr>

<? 
$RowClass = 'c';
foreach($Tests as $Row){
   printf('<tr class="%s">',$RowClass); echo "\n";
   $Link = $Row[TEST_LINK];
   $Name = $Row[TEST_NAME];

   if (isset($Data[$Link])){
      $v = $Data[$Link];     

      printf('<td><a href="benchmark.php?test=%s&amp;lang=%s&amp;id=%d">%s</a></td>', 
         $Link, $SelectedLang, $v[N_ID], $Name);
                
      if ($v[N_LINES] >= 0){ 
      
         if ($v[N_N]==0){ $n = '?'; } else { $n = '&nbsp;'.number_format($v[N_N]); }                         
                  
         $cpuValue = $v[N_FULLCPU];
         if ($cpuValue<1 && $cpuValue!=0){ $cpuValue = -1/$cpuValue; }
         $memValue = $v[N_MEMORY];
         if ($memValue<1 && $memValue!=0){ $memValue = -1/$memValue; }
         $locValue = $v[N_LINES];
         if ($locValue<1 && $locValue!=0){ $locValue = -1/$locValue; }

         printf('<td class="r">%s</td><td class="r">%s</td>
               <td class="r">%s</td><td class="r"><span class="s">%s</span></td>', 
            PF($v[N_FULLCPU]), PF($v[N_MEMORY]), PF($v[N_LINES]), $n);                   

      } else {      
         $r = FALSE;
         if ($v[N_LINES] == PROGRAM_ERROR){ $message = 'Error'; } 
         elseif ($v[N_LINES] == PROGRAM_TIMEOUT){ $message = 'Timout'; } 
         elseif ($r = $v[N_LINES] == NO_COMPARISON){ $message = '<span class="s">No '.$Langs[$v[N_LANG]][LANG_NAME].'</span>'; } 
         else {$message = 'X'; } 
         
         if ($r) { printf('<td></td><td></td><td colspan="2" class="r">%s</td>', $message); }
         else { printf('<td class="r">%s</td><td></td><td></td><td></td>', $message); }
      }

   } else {
      printf('<td><a href="benchmark.php?test=%s&amp;lang=%s">%s</a></td>', 
         $Link, $SelectedLang, $Name); echo "\n";
      $message = 'No&nbsp;program';
      printf('<td class="r">%s</td><td></td><td></td><td></td>', $message); 
   }
   echo "</tr>\n";
   if ($RowClass=='a'){ $RowClass='c'; } else { $RowClass='a'; } 
}
?>   

<tr><td colspan="5">&nbsp;</td></tr>
   


<!-- // SUMMARY /////////////////////////////////////////////////// -->   

<tr class="b"><td colspan="6" >
<a class="ab" href="#summary" name="summary">&nbsp;summary</a>
</td></tr>

<tr>
<th colspan="6"><?=$ShortName;?> better : <?=$ShortName2;?> better</th>
</tr>  
    
<tr class="a">
<th class="c">&nbsp;</th>
<th>Full&nbsp;CPU Time</th>
<th>Memory Use</th>
<th>Code Lines</th>
<th class="c">&nbsp;</th>
<th class="c">&nbsp;</th>
</tr>   




<? // this should be in a function

$fullb = 0; $fullw = 0; $memb = 0; $memw = 0; 
$cpub = 0; $cpuw = 0; $linesb = 0; $linesw = 0;

foreach($Tests as $Row){
   $Link = $Row[TEST_LINK];
   if (isset($Data[$Link])){
      $v = $Data[$Link];    
      
      if ($v[N_LINES] > 0){           
      
         if ($v[N_FULLCPU] <= 0.999){ $fullw++; } 
         elseif ($v[N_FULLCPU] >= 1.001){ $fullb++; } 
         
         if ($v[N_MEMORY] <= 0.999){ $memw++; } 
         elseif ($v[N_MEMORY] >= 1.001){ $memb++; }   
         
         if ($v[N_CPU] <= 0.999){ $cpuw++; } 
         elseif ($v[N_CPU] >= 1.001){ $cpub++; }   
         
         if ($v[N_LINES] <= 0.999){ $linesw++; } 
         elseif ($v[N_LINES] >= 1.001){ $linesb++; }                                                                    
      }      
   }
}
?>

<tr class="a">
<td class="c">&nbsp;</td>
<td class="r"><?=$fullb;?>:<?=$fullw;?></td>
<td class="r"><?=$memb;?>:<?=$memw;?></td>
<td class="r"><?=$linesb;?>:<?=$linesw;?></td>
<td class="c">&nbsp;</td>
<td class="c">&nbsp;</td>
</tr>
<tr><td colspan="6">&nbsp;</td></tr>  

</table>
</td></tr>


<tr><td><h4 class="rev"><a class="arev" href="#about" name="about">&nbsp;about <?=$LangName;?></a></h4></td></tr>
<tr><td><?=$About;?></td></tr>  

</table>
