<!--#set var="TITLE" value="Exceptions" -->
<!--#set var="KEYWORDS" value="performance, benchmark, 
perl, python,
exceptions, language, compare, cpu, memory" -->

<?php require("../../html/testtop.php");
      testtop("Exception Handling"); ?>

<h4>About this test</h4>
<p>
  For this test, each program should be implemented in the <a
  href="../../method.shtml#sameway"><i>same way</i></a>.
<p>
  This test compares the speed of exceptions as implemented in
  different languages.  Each test throws N exceptions.  Visit the <a
  href="detail.shtml">detail page</a> to see results for different
  values of N.
<p>
  Each test program throws a bunch of exceptions, half of which are caught
  in the calling function and half are to be caught by the caller's caller.
  Those languages that can limit catching exceptions at a given stack frame
  to a given exception type will perform better than those that can't,
  because the latter will have to catch all exceptions in the caller, and
  re-throw those that are not supposed to be caught at that level.  For
  instance, Perl implements exceptions via its eval/die functions, and you
  must catch all exceptions and re-throw those that do not belong to you.
  By contrast, Python allows you to specify just those exceptions you wish
  to catch, and others are automatically passed up the call chain.
<p>
  This is a fairly meaningless test in terms of the measured results
  :-) (Most normal programs do not use exceptions to the extent that
  they factor significantly into the overall performance).  However,
  it is interesting to compare the different exception implementations
  in each language.

<h4>Observations</h4>
<p>
  In Ruby, if an exception occurs, the code that caused the exception
  can easily be restarted with <b>retry</b>, which is a neat feature.

<p>
  Rep exceptions are fast ... but we are not really instantiating
  exception objects and throwing them, we are only throwing a tag and
  value, which is pretty much like the Lisp equivalent of a GOTO.

<p>
  I think that using exceptions to implement normal program flow is bad
  programming style.  Python does this in such situations as trying to
  open a non-existant file.  I believe Kernighan and Plauger deprecate
  this behavior in their book <i>Elements of Programming Style</i> (I
  need to find my old copy to make sure of this).

<p>
  When compiled with -O3, the G++ (2.95.3) program dumps core.  It
  runs fine compiled with -O2.

<h4><a href="alt/">Alternates</a></h4>
<p>
  <i>This section is for displaying alternate solutions that are either
  slower than ones above or perhaps don't quite meet my criteria for
  the competition, but are otherwise worthy of comment.</i>
<ul>
<li>
  Mark Baker contributed a slightly faster <a
  href="alt/except.python2.python">Python</a> program, however, I've
  made it an alternate for now because I'm looking for solutions that
  instantiate an exception object.  If I change the test to omit that
  restriction, I'll use this version instead of the current one.
</ul>

<!--#include virtual="../../html/nav.shtml" -->
<!--#include virtual="../../html/footer.shtml" -->
