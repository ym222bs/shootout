<?php 
// Copyright (c) Isaac Gouy 2004, 2005

// LIBRARIES ////////////////////////////////////////////////

require_once(LIB_PATH.'lib_common.php'); 
require_once(LIB); 

// DATA ///////////////////////////////////////////


list($Incl,$Excl) = ReadIncludeExclude();
$Tests = ReadUniqueArrays('test.csv',$Incl);
uasort($Tests, 'CompareTestName');

$Langs = ReadUniqueArrays('lang.csv',$Incl);
uasort($Langs, 'CompareLangName');

if (isset($HTTP_GET_VARS['test'])){ $T = $HTTP_GET_VARS['test']; } 
elseif (!isset($T)){ $T = 'ackermann'; }

if (isset($HTTP_GET_VARS['lang'])){ $L = $HTTP_GET_VARS['lang']; }
elseif (!isset($L)){ $L = 'all'; } 

if (isset($HTTP_GET_VARS['lang2'])){ $L2 = $HTTP_GET_VARS['lang2']; }
elseif (!isset($L2)){  
   if ($L=='all'){ $L2 = $L; }
   else { $L2 = $Langs[$L][LANG_COMPARE]; }
}


if (isset($HTTP_GET_VARS['id'])){ $I = $HTTP_GET_VARS['id']; } 
else { $I = 0; }

if (isset($HTTP_GET_VARS['sort'])){ $S = $HTTP_GET_VARS['sort']; } 
else { $S = 'fullcpu'; }

$MetaKeywords = '';


// PAGES ///////////////////////////////////////////////////
// There are 4 kinds of test/lang combination
// - all tests all languages - Scorecard
// - all tests one language  - Head to Head | Ranking
// - one test all languages  - Benchmark
// - one test one language   - Program

$Page = & new Template(LIB_PATH);
$Body = & new Template(LIB_PATH); 

if ($T=='all'){
   if ($L=='all'){    // Scorecard 
   
      require_once(LIB_PATH.'lib_scorecard.php');       
   
      if (isset($HTTP_GET_VARS['calc'])){ $Action = $HTTP_GET_VARS['calc']; } 
      else { $Action = 'Calculate'; }   
  
      $Title = 'Create your own Overall Scores';
      $TemplateName = 'scorecard.tpl.php';

      $About = & new Template(ABOUT_PATH);
      $AboutTemplateName = 'scorecard-about.tpl.php'; 

      $W = Weights($Tests, $Action, $HTTP_GET_VARS, $_COOKIE);
      setcookie('weights', PackCSV($W), time()+(60*60*24*180), '/', 'shootout.alioth.debian.org');      
      $Body->set('W', $W);                                               

      $Body->set('Data', ScoreData(DATA_PATH.'data.csv', $Tests, $Langs, $Incl, $Excl)); 
      $metaRobots = '<meta name="robots" content="noindex,nofollow,noarchive" />';


      } else {           // Head to Head 
      
      require_once(LIB_PATH.'lib_headtohead.php');       
  
      $LangName = $Langs[$L][LANG_FULL];    
      $Title = $LangName.' benchmarks'; 

      if (isset($metaRobots)){ // Assume it's one of our special pages which should be indexed
         $metaRobots = '<meta name="robots" content="index,follow,noarchive" />'; 
         $Family = $Langs[$L][LANG_FAMILY];
         $MetaKeywords = '<meta name="keywords" content="'.
            $Title.' '.$Family.' programs '.$Family.' benchmark '.$Family.' language" />'.  
            '<meta name="description" content="'.
            'Compare '.$LangName.' performance on benchmark programs." />';  
      } else {
         $metaRobots = '<meta name="robots" content="noindex,nofollow,noarchive" />'; 
      }

      $Title = $LangName.' benchmarks';

      if ($L!=$L2){ 
         $TemplateName = 'headtohead.tpl.php'; 
         //$Title = 'Compare '.$LangName.' to '.$Langs[$L2][LANG_FULL];       
         $Body->set('Data', HeadToHeadData(DATA_PATH.'ndata.csv',$Langs,$Incl,$Excl,$L,$L2));                      
      } else { 
         $TemplateName = 'language.tpl.php'; 
         //$Title = $LangName.' benchmarks';  
         $Body->set('Data', LanguageData(DATA_PATH.'ndata.csv',$Langs,$Incl,$Excl,$L,$L2));                        
      }
      
      $About = & new Template(ABOUT_PATH);
      $AboutTemplateName = $L.SEPARATOR.'about.tpl.php';
      if (! file_exists(ABOUT_PATH.$AboutTemplateName)){ $AboutTemplateName = 'blank-about.tpl.php'; }
      $About->set('Version', HtmlFragment(VERSION_PATH.$L.SEPARATOR.'version.php'));
        
   }

} elseif ($L=='all'){ // Benchmark  

      $TestName = $Tests[$T][TEST_NAME];
      $Title = $TestName.' benchmark'; 
      $TemplateName = 'benchmark.tpl.php'; 

      $About = & new Template(ABOUT_PATH);
      $AboutTemplateName = $T.SEPARATOR.'about.tpl.php'; 
      if (! file_exists(ABOUT_PATH.$AboutTemplateName)){ $AboutTemplateName = 'blank-about.tpl.php'; }
      $Body->set('Data', ReadSelectedDataArrays(DATA_PATH.'data.csv', $T, $Incl) );
      $metaRobots = '<meta name="robots" content="noindex,nofollow,noarchive" />';

} else {              // Program

      $TestName = $Tests[$T][TEST_NAME];
      $LangName = $Langs[$L][LANG_FULL];       
      $TemplateName = 'program.tpl.php';
      $Title = $TestName.' '.$LangName.IdName($I).' program';

      // NOTE Sometimes there's an alternative program for the benchmark test and language
      //      so we need to look for files with a particular Id, as-well-as test and language    

      $Id = '';
      if ($I > 0){ $Id = SEPARATOR.$I; }            
      $About = & new Template(ABOUT_PROGRAMS_PATH);
      $AboutTemplateName = $T.SEPARATOR.$L.$Id.SEPARATOR.'about.tpl.php'; 
      if (! file_exists(ABOUT_PROGRAMS_PATH.$AboutTemplateName)){ $AboutTemplateName = 'blank-about.tpl.php'; }

      $Body->set('Data', ReadSelectedDataArrays(DATA_PATH.'data.csv', $T, $Incl) );      
      $Body->set('Code', HtmlFragment( CODE_PATH.$T.SEPARATOR.$L.$Id.'.code' ));    
      $Body->set('Log', HtmlFragment( LOG_PATH.$T.SEPARATOR.$L.$Id.'.log' ));
      $Body->set('Id', $I);
      $Body->set('Title', $Title);
      $metaRobots = '<meta name="robots" content="noindex,nofollow,noarchive" />';

}

 
// TEMPLATE VARS //////////////////////////////////////////////// 


$Page->set('PageTitle', $Title.BAR.SITE_TITLE);
$Page->set('BannerTitle', BANNER_TITLE);
$Page->set('FaqTitle', FAQ_TITLE);
$Page->set('Sort', $S);

$Body->set('Tests', $Tests);
$Body->set('SelectedTest', $T);
$Body->set('Langs', $Langs);
$Body->set('SelectedLang', $L);
$Body->set('SelectedLang2', $L2);
$Body->set('Sort', $S);
$Body->set('Excl', $Excl);

$About->set('SelectedTest', $T);
$About->set('SelectedLang', $L);
$About->set('Sort', $S);
$Body->set('About', $About->fetch($AboutTemplateName));

$Page->set('PageBody', $Body->fetch($TemplateName));
$Page->set('Robots', $metaRobots);
$Page->set('MetaKeywords', $MetaKeywords);

echo $Page->fetch('page.tpl.php');
?>

