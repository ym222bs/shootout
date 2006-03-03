<?php      
echo '<?xml version="1.0" encoding="iso-8859-1" ?>';      
$D = filemtime('../data/data.csv');
$G = filemtime('./gp4/data/data.csv');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow,archive" /><meta name="revisit" content="1 days" />

<meta name="keywords" content="benchmarking fast programming language benchmark performance benchmarks shootout program" />
<meta name="description" content="Compare programming language performance on a few dozen flawed benchmarks and contribute faster more elegant programs." />

<title>The Computer Language Shootout Benchmarks</title>
<link rel="stylesheet" type="text/css" href="./benchmark.css" />
<link href="./feeds/rss.xml" rel="alternate" type="application/rss+xml" title="Computer Language Shootout" />
<link rel="shortcut icon" href="./favicon.ico" />
<style type="text/css" media="all">
   .hf, .rev, .arev, .arev:visited { background-color: navy; }
   .arevCore { background-color: navy; }
   .arevSandbox { background-color: #cc9900; }
   .arevOld { background-color: gray; }   
   .arevGP4 { background-color: #7b59de; }
   h4 { padding-bottom: 0.5em; }   
</style>
</head>
<body>

<table class="hf"><tr>
<td><h2>The&nbsp;Computer&nbsp;Language&nbsp; <br/>Shootout&nbsp;Benchmarks</h2></td>
<td class="hftag"><a href="./feeds/rss.xml"><img src="./orangexml.gif" alt="Really Simple Syndication" title="Really Simple Syndication" /></a></td>
</tr></table>

<div>
<h4>Benchmarking programming languages?</h4>
<p>How can we benchmark a programming language?<br/>
We can't - we benchmark programming language implementations.</p>
<p>How can we benchmark language implementations?<br/>
We can't - <strong>we measure particular programs</strong>.</p>
</div>

<div>
<h4>Contribute:</h4>
<p>these programming language comparisons are only as good as the programs contributed by the community</p>
<ul>
<li>you can <a href="faq.php?&#35;contributeprogram" title="FAQ: How can I help?">contribute faster more-elegant programs</a>&nbsp;(FAQ)</li>
<li>you can <a href="faq.php?&#35;contributebenchmark" title="FAQ: How can I contribute a new benchmark?">suggest new benchmark comparisons</a>&nbsp;(FAQ)</li>
</ul>
</div>

<div>
<h4>Use:</h4>
<p>these are particular truths, they are not general truths</p>
<ul>
<li>read <a href="faq.php" title="Frequently Asked Questions"><strong>the FAQ</strong></a> and read about <a href="miscfile.php?file=benchmarking&amp;title=Flawed Benchmarks" title="Flawed benchmarks - Are there any other kind?"><strong>flawed benchmarks</strong></a></li>
<li>learn about the languages - read the programs</li>
<li>understand that <em>the faster program</em> may become <em>the slower program</em> when the workload changes</li>
</ul>
</div>

<div>
<h4>Start:</h4>
<p>choose an OS : architecture, and click the colourful link to browse our current benchmarks, programs, languages and measurements</p>
<table>
<tr>
<td>
<p class="rs"><? printf('%s', gmdate("d M Y", $D)) ?></p>
<h5>
<a class="arevCore" 
title="Browse the Debian : AMD&#8482; Sempron&#8482; Computer Language Shootout"
href="./debian/">&nbsp;Debian&nbsp;:&nbsp;AMD&#8482;&nbsp;Sempron&#8482;&nbsp;</a></h5>
</td>
<td>
<p class="rs"><? printf('%s', gmdate("d M Y", $G)) ?></p>
<h5>
<a class="arevGP4" 
title="Browse the Gentoo : Intel&#174; Pentium&#174; Computer Language Shootout"
href="./gp4/">&nbsp;Gentoo&nbsp;:&nbsp;Intel&#174;&nbsp;Pentium&#174;&nbsp;4&nbsp;</a></h5>
</td>
</tr>
</table>
</div>

<div>
<h4>Extra:</h4>
<p>More language implementations and alpha benchmarks</p>
<table>
<tr>
<td>
<p class="rs"><? printf('%s', gmdate("d M Y", $D)) ?></p>
<h5>
<a class="arevSandbox" 
title="Browse the Debian : AMD&#8482; Sempron&#8482; more language implementations and alpha benchmarks"
href="./sandbox/">&nbsp;Debian&nbsp;:&nbsp;AMD&#8482;&nbsp;Sempron&#8482;&nbsp;</a></h5>
</td>
<td>
<p class="rs"><? printf('%s', gmdate("d M Y", $G)) ?></p>
<h5>
<a class="arevSandbox" 
title="Browse the Gentoo : Intel&#174; Pentium&#174; more language implementations and alpha benchmarks"
href="./gp4sandbox/">&nbsp;Gentoo&nbsp;:&nbsp;Intel&#174;&nbsp;Pentium&#174;&nbsp;4&nbsp;</a></h5>
</td>
</tr>
</table>
</div>

<div>
<h4>For auld lang syne:</h4>
<p>Look back at the old 2001 Doug Bagley Benchmarks</p>
<table>
<tr>
<td>
<p class="rs"><? printf('%s', gmdate("d M Y", $D)) ?></p>
<h5>
<a class="arevOld" 
title="Look back at the Old 2001 Doug Bagley Benchmarks"
href="./old/">&nbsp;Debian&nbsp;:&nbsp;AMD&#8482;&nbsp;Sempron&#8482;&nbsp;</a>
</h5>
</td>
</tr>
</table>
</div>

<p class="center"><br/><br/>
<a href="miscfile.php?file=license&amp;title=Revised BSD license" title="Software contributed to the Shootout is published under this revised BSD license" >
   <img src="./open_source_button.png" alt="Revised BSD license" height="31" width="88" />
</a>
</p>
</body>
</html>
