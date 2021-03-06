#!/usr/bin/php
<?

// Copyright (c) Isaac Gouy 2009

// CONSTANTS ////////////////////////////////////////////////

define('HTTP_DIR', 'I:/Abyss Web Server/htdocs/websites');
//$Sites = array('gp4','debian','u32q');
$Sites = array('u32q');

// FUNCTIONS ///////////////////////////////////////////


function extractLogDates($site){
   $d = HTTP_DIR.'/'.$site.'/code';
   $h = opendir($d)
      or die("Couldn't open $d");

   $fs = array();
   while ($each = readdir($h)){
      if (preg_match('/\.log$/i',$each)){
         $fs[] = $d.'/'.$each;
      }
   }
   
   $sitelist = includes($site);
   $freq = array();
   $sum = 0.0;
   foreach ($fs as $each){
      $parts = explode(".",$each);
      $lang = $parts[2];
      if (isset($sitelist[$lang])){

         $fp = @fopen($each,"r")
            or die("Couldn't open $each");

         while ($line = fgets($fp, 128))
            if ($found = preg_match('/([A-Z][a-z]{2})[0-9A-Z\s\:]+(\d{4})/', $line,$match))
               break;

         if ($found){
            $sum++;
            $mth = month($match[1]);
            $yr = intval($match[2]);     
            if (!isset($freq[$yr][$mth][$site])){
               $freq[$yr][$mth][$site] = 0;
            } else {
               $freq[$yr][$mth][$site] += 1;
            }
         }

         fclose($fp)
            or die("Couldn't close $each");
      }
   }
   closedir($h);

   $a = array();
   foreach ($freq as $yr => $mths)
      foreach ($mths as $mth => $counts)
         if (isset($counts[$site]) && $counts[$site]>0){
            $a[] = array($yr,$mth,$site,$counts[$site]/$sum);
         }

   usort($a,'CompareYearMonth');
   
   $cumulative = 0.0;
   for ($k=0; $k<sizeof($a); $k++){
      $cumulative += $a[$k][3];
      $a[$k][3] = $cumulative;
   }

   return $a;
}


function month($s){
   switch ($s){
      case "Jan": return 1;
      case "Feb": return 2;
      case "Mar": return 3;
      case "Apr": return 4;
      case "May": return 5;
      case "Jun": return 6;
      case "Jul": return 7;
      case "Aug": return 8;
      case "Sep": return 9;
      case "Oct": return 10;
      case "Nov": return 11;
      case "Dec": return 12;
      default: echo $s, '\n';
   }
}

define('INCL_LINK',0);

function includes($site){
   $a = array();
   $f = @fopen(HTTP_DIR.'/'.$site.'/include.csv','r') or die('Cannot open ./include.csv');
   $row = @fgetcsv($f,1024,','); // heading row
   while (!@feof ($f)){
      $row = @fgetcsv($f,1024,',');
      if (!is_array($row)){ continue; }
      if (isset($row[INCL_LINK]{0})){ $a[ $row[INCL_LINK] ] = 0; }
   }
   @fclose($f);
   return $a;
}

function CompareYearMonth($a,$b){
   if ($a[0]==$b[0]){
      if ($a[1]==$b[1]){ return 0; }
      else { return ($a[1]<$b[1]) ? -1 : 1; }
   }
   else { return ($a[0]<$b[0]) ? -1 : 1; }
}

function YrMth(&$p){
   return $p[0]*12+$p[1];
}

// MAIN ////////////////////////////////////////////////

$p = array();
foreach ($Sites as $each){
   $tmp = extractLogDates($each);
   
   // find first and last measurement batch in time
   foreach ($tmp as $each){
      if (!isset($firstYrMth)) $firstYrMth = YrMth($each);
      $first = min($firstYrMth,YrMth($each));
      if (!isset($lastYrMth)) $lastYrMth = YrMth($each);
      $last = max($lastYrMth,YrMth($each));
   }
   $p[] = $tmp;
}

for ($i=0; $i<sizeof($p); $i++){
   // left shift measurement batches to time zero
   for ($k=0; $k<sizeof($p[$i]); $k++){
      $p[$i][$k][1] = YrMth($p[$i][$k]) - $firstYrMth;
      printf("%s,%s,%s,%0.2f\n", $p[$i][$k][0], $p[$i][$k][1], $p[$i][$k][2], $p[$i][$k][3]);
   }
}



