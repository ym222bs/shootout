<p>Each program should do the <a href="faq.php?sort=<?=$Sort;?>#samething"><b>same&nbsp;thing</b></a>.</p>


<p>Each program should
<ul>
  <li>read line-by-line a redirected <a href="http://en.wikipedia.org/wiki/Fasta_format">FASTA format</a> file from stdin</li>
  <li>for each sequence:
  <ul>
  <li>write the id, description, and the reverse-complement sequence in <a href="http://en.wikipedia.org/wiki/Fasta_format">FASTA format</a> to stdout</li>
  </ul>
  </li>
</ul>
</p>


<p>We use the FASTA file generated by the <a href="benchmark.php?test=fasta&lang=all&sort=<?=$Sort;?>">fasta benchmark</a> as input for this benchmark. Note: the file may include both lowercase and uppercase codes.</p>

<p>Correct output for, N = 1000, this 10KB <a href="iofile.php?test=<?=$SelectedTest;?>&lang=<?=$SelectedLang;?>&sort=<?=$Sort;?>&file=input">input file</a> is in this 10KB <a href="iofile.php?test=<?=$SelectedTest;?>&lang=<?=$SelectedLang;?>&sort=<?=$Sort;?>&file=output">output file</a>.</p>


<p>We use these code complements:
<pre>
code  meaning   complement
A    A                   T
C    C                   G
G    G                   C
T/U  T                   A
M    A or C              K
R    A or G              Y
W    A or T              W
S    C or G              S
Y    C or T              R
K    G or T              M
V    A or C or G         B
H    A or C or T         D
D    A or G or T         H
B    C or G or T         V
N    G or A or T or C    N
</pre></p><br/>




