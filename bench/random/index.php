<!--#set var="TITLE" value="Random Number Generator" -->
<!--#set var="KEYWORDS" value="performance, benchmark, 
computer, language, compare, cpu, memory,
random number generator" --> 

<?php require("../../html/testtop.php");
      testtop("Random Number Generator"); ?>

<h4>About this test</h4>
<p>
  For this test, each program should be implemented in the <a
  href="../../method.shtml#sameway"><i>same way</i></a>.
<p>
  The purpose of this test is to implement a function that generates
  a random double-precision floating point number using a naive
  (i.e. inexpensive) algorithm.  The function we implement for this
  test will also be used in the <a href="../heapsort/">Heapsort</a>
  test.  The algorithm for the function is a <i>linear congruential
  generator</i> as described in <b>Numerical Recipes in C</b> by
  Press, Flannery, Teukolsky, Vetterling, section 7.1.  It goes like
  this:

<p>
<table align="center" border="1" cellspacing="2" cellpadding="4" bgcolor="#c0e0e0" width="50%">
<tr><td align="center">
  <font size="+2"><i>
  S[j] = (A * S[j-1] + C) modulo M<br>
  R = N * S[j] / M
  </i></font>
</td></tr>
<tr><td>
where A (multiplier), C (increment), and M (modulus) are appropriately
chosen integral constants.  S[j] is the integral seed which is
calculated from the previous seed (S[j-1]).  R is the random
double-precision floating point number calculated from the seed and
normalized to the interval [N,0].
</td></tr>
</table>

<p>
  For this test I would like to see each program use symbolic
  constants (or whatever is closest) to define the A, C, and M
  constants in the algorithm.  The program shouldn't use literal
  constants. 
<p>
  For an input argument of 1000, the test should produce the answer:
  <!--#include virtual="Output" -->

<h4>Observations</h4>
<p>
  It sure would be nice to have a standard way to format floats :-)
<p>
  In order to be able to add bigloo I had to use different constants
  for the random function since bigloo's fixnums have 2 less bits than
  a C integer.
<p>
  The <a href="random.se">SmallEiffel solution</a> (submitted by
  Steve Thompson) is implemented in multiple files.  See also the <a
  href="../Include/randomnumber.e">RandomNumber</a> class.
<p>
  The following programs are currently disqualified because they don't
  output the correct answers:
  <a href="random.bigforth">bigforth</a>, 
  <a href="random.icon">icon</a>, 
  <a href="random.rep">rep</a>, 
  <a href="random.njs">njs</a>.

<h4><a href="alt/">Alternates</a></h4>
<p>
  <i>This section is for displaying alternate solutions that are either
  slower than ones above or perhaps don't quite meet my criteria for
  the competition, but are otherwise worthy of comment.</i>
<ul>
<li>
  Miguel Sofer has contributed a very fast <a href="random.tcl">Tcl
  solution</a> that deserves mention because of the dramatic speedup
  he has accomplished.  Read more about it <a
  href="http://mini.net/cgi-bin/wikit/1173.html">here</a>.
<li>
  Here's the original <a href="alt/random.tcl2.tcl">Tcl solution</a> from
  Kristoffer Lawson, which is arguably written in a more conventional Tcl
  style than the faster version from Miguel.
</ul>

<!--#include virtual="../../html/nav.shtml" -->
<!--#include virtual="../../html/footer.shtml" -->
