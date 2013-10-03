<?php

$title="Focus Battle Resolution";
require_once('header.php');

?>

<!--<MarkdownReplacement with="competition-Focus-Battle-Resolution.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="focus-battle-resolution">Focus Battle Resolution</h2>
<p>The battle resolution can be stated 2 different ways:</p>
<blockquote>
<p>An ant will be killed by an enemy ant</p>
<p>if an enemy ant in range</p>
<p>is surrounded by less (or the same) of its own enemies than the ant</p>
</blockquote>
<p>-or-</p>
<blockquote>
<p>An ant will kill an enemy ant if</p>
<p>an enemy in range</p>
<p>is surrounded by more (or the same) enemies than its target</p>
</blockquote>
<p>If you don't understand either of those, don't worry, that's what the rest of this page is for.</p>
<h1 id="the-intuitive-physical-world-explanation">The Intuitive Physical World Explanation</h1>
<p>An ant is a very simple creature. In fact it will only take two actions on its own without an external command.</p>
<p>The <strong>first</strong> is to create another ant from food that is close enough to it. This action is of course quite complex for the ant, taking much fine and detailed work (which is why the food must be very close). The results though are quite simple; if the food is close enough it is turned into another ant of the same color. The only exception is when ants of differing colors manage to get close enough at the same time; the food is destroyed when both of them try and work with it.</p>
<p>The <strong>second</strong> action an ant will take is to fight with enemy ants. This is a much simpler action than "spawning" so can be carried out at a greater distance (although the details aren't completely known it involves much flailing of legs). The results can be a little more complex to understand than the "spawning" action. </p>
<p>The simplest case is when two lone ants of differing colors get close enough to each other and therefore must engage in combat. They will both focus solely on the other opposing ant.  Being evenly matched in focus, both end up killing the other.</p>
<p>When a lone ant meets two opposing ants at the same time, it splits its focus between both enemies. They, of course, can concentrate on it completely. This means the lone ant, being more distracted, dies and the other two survive. So you can see for an ant to survive the opponents it is facing it must have more focus than they do. If any opponent is equally or less occupied the ant will die.</p>
<p>Ants do not distinguish between enemy colors. If ants of three different colors, say green, red and blue, came together so they could all reach each other, then each ant would have its attention split two ways and all three would die. But if a green ant has a red ant on one side and a blue ant on the other, where the red and blue ant can not reach each other, then the green ant would die because its attention is split while both the red and blue ants would focus solely on it.</p>
<p>Hopefully this helps you understand how ant fights work. In order for you to double check your understanding here is a slightly more complex example for you to work out the result:</p>
<blockquote>
<p>There are two blue ants, Bob and Bill, and two red ants, Roy and Ralph wandering around. After moving, Bill has
stepped close enough to fight with both red ants while Bob only moved close enough to fight with Roy. The question for
you is which ants die and which survive?</p>
</blockquote>
<p>No peeking at the answer below until you have come up with your own.</p>
<div class="codehilite"><pre>Bob  <span class="o">-&gt;</span> B..
        <span class="kc">...</span>
Bill <span class="o">-&gt;</span> B.R <span class="o">&lt;-</span> Roy
        <span class="kc">...</span>
        ..R <span class="o">&lt;-</span> Ralph
</pre></div>


<p>You got an answer already? Don't keep going without one.</p>
<p>The situation above is that the blue ant Bill and the red ant Roy have their attention split between two opponents. Bob and Ralph are able to concentrate on just one opponent, who happens to have his attention split. So Bill and Roy die while Bob and Ralph survive to fight another day.</p>
<p>Pretty easy right? Of course it is. Every fight works the same way, if an ant has its focus split up among less enemies than any of those enemy ants focus is split, it survives. Now you're ready to dive into the rest of this document that gives a little more technical explanation and several examples.</p>
<h1 id="more-technical-description">More Technical Description</h1>
<p>Each ant may have some enemy ants in range.  It is considered to be "occupied" with the task fighting those ants.  Each ant may have a different number of enemies it is "occupied" by.  Ants that are occupied by less enemies are considered to be more focused on their task of killing those enemies than an ants that is occupied by more enemies.  Ants that are equally occupied are able to kill each other.</p>
<div class="codehilite"><pre><span class="p">......</span><span class="n">C</span>
<span class="n">C</span><span class="p">.</span><span class="n">A</span><span class="p">.</span><span class="n">B</span><span class="p">.</span><span class="n">C</span>
<span class="p">......</span><span class="n">C</span>
</pre></div>


<ul>
<li>ant A is in range of ant B</li>
<li>ant A is occupied (or fighting) 2 enemies</li>
<li>ant B is occupied (or fighting) 4 enemies</li>
<li>ant B has more on his mind, is more distracted and less effective than ant A</li>
<li>ant A can therefore kill ant B</li>
<li>other stuff happens with the C ants, but we are just focusing on A and B<ul>
<li>C would kill both A and B even if A and B were not next to each other</li>
</ul>
</li>
</ul>
<p>Each ant may have more than one ant that it can kill and it may also have more than one ant that it can be killed by.  An ant may both kill another ant, and be killed in the same turn.</p>
<p>To try and calculate it by hand, just write the number of enemy ants surrounding each ant:</p>
<div class="codehilite"><pre><span class="p">.....</span><span class="mf">.1</span>
<span class="mi">1</span> <span class="mi">2</span> <span class="mi">4</span> <span class="mi">1</span>
<span class="p">.....</span><span class="mf">.1</span>
</pre></div>


<p>Then, if there is any ant is next to an enemy with an equal or lesser number, it will die:</p>
<div class="codehilite"><pre><span class="p">......</span><span class="n">C</span>
<span class="n">C</span><span class="p">.</span><span class="n">x</span><span class="p">.</span><span class="n">x</span><span class="p">.</span><span class="n">C</span>
<span class="p">......</span><span class="n">C</span>
</pre></div>


<h1 id="scenarios">Scenarios</h1>
<p>These all assume attackradius2 = 5, which is the following shape (with some radii calculated for you):</p>
<div class="codehilite"><pre><span class="p">.......</span>    <span class="p">..</span><span class="mf">.9</span><span class="p">...</span>
<span class="p">..</span><span class="n">xxx</span><span class="p">..</span>    <span class="mf">.85458</span><span class="p">.</span>
<span class="p">.</span><span class="n">xxxxx</span><span class="p">.</span>    <span class="mf">.52125</span><span class="p">.</span>
<span class="p">.</span><span class="n">xxAxx</span><span class="p">.</span>    <span class="mi">9410149</span>
<span class="p">.</span><span class="n">xxxxx</span><span class="p">.</span>    <span class="mf">.52125</span><span class="p">.</span>
<span class="p">..</span><span class="n">xxx</span><span class="p">..</span>    <span class="mf">.85458</span><span class="p">.</span>
<span class="p">.......</span>    <span class="p">..</span><span class="mf">.9</span><span class="p">...</span>
</pre></div>


<h2 id="one-on-one">One-on-One</h2>
<ul>
<li>
<p>Both ants die</p>
<div class="codehilite"><pre><span class="p">.....</span>    <span class="p">.....</span>    <span class="p">.....</span>
<span class="p">.</span><span class="n">A</span><span class="p">.</span><span class="n">B</span><span class="p">.</span> <span class="o">-&gt;</span> <span class="mf">.1.1</span><span class="p">.</span> <span class="o">-&gt;</span> <span class="p">.</span><span class="n">x</span><span class="p">.</span><span class="n">x</span><span class="p">.</span>
<span class="p">.....</span>    <span class="p">.....</span>    <span class="p">.....</span>
</pre></div>


</li>
</ul>
<h2 id="two-on-one">Two-on-One</h2>
<ul>
<li>
<p>A dies</p>
<div class="codehilite"><pre><span class="p">...</span><span class="n">B</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.1</span><span class="p">.</span>    <span class="p">...</span><span class="n">B</span><span class="p">.</span>
<span class="p">.</span><span class="n">A</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="mf">.2</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">.</span><span class="n">x</span><span class="p">...</span>
<span class="p">...</span><span class="n">B</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.1</span><span class="p">.</span>    <span class="p">...</span><span class="n">B</span><span class="p">.</span>
</pre></div>


</li>
</ul>
<h2 id="one-on-one-on-one">One-on-One-on-One</h2>
<ul>
<li>
<p>All die</p>
<div class="codehilite"><pre><span class="p">...</span><span class="n">B</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.2</span><span class="p">.</span>    <span class="p">...</span><span class="n">x</span><span class="p">.</span>
<span class="p">.</span><span class="n">A</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="mf">.2</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">.</span><span class="n">x</span><span class="p">...</span>
<span class="p">...</span><span class="n">C</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.2</span><span class="p">.</span>    <span class="p">...</span><span class="n">x</span><span class="p">.</span>
</pre></div>


</li>
</ul>
<h2 id="ant-sandwich">Ant Sandwich</h2>
<ul>
<li>The B ant in the center dies</li>
<li>
<p>A and C both live</p>
<div class="codehilite"><pre><span class="p">.....</span>    <span class="p">.....</span>    <span class="p">.....</span>
<span class="n">A</span><span class="p">.</span><span class="n">B</span><span class="p">.</span><span class="n">C</span> <span class="o">-&gt;</span> <span class="mf">1.2.1</span> <span class="o">-&gt;</span> <span class="n">A</span><span class="p">.</span><span class="n">x</span><span class="p">.</span><span class="n">C</span>
<span class="p">.....</span>    <span class="p">.....</span>    <span class="p">.....</span>
</pre></div>


</li>
</ul>
<h2 id="one-on-two-on-one">One-on-Two-on-One</h2>
<ul>
<li>
<p>B and C die</p>
<div class="codehilite"><pre><span class="p">...</span><span class="n">B</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.3</span><span class="p">.</span>    <span class="p">...</span><span class="n">x</span><span class="p">.</span>
<span class="p">.</span><span class="n">A</span><span class="p">.</span><span class="n">A</span><span class="p">.</span> <span class="o">-&gt;</span> <span class="mf">.2.2</span><span class="p">.</span> <span class="o">-&gt;</span> <span class="p">.</span><span class="n">A</span><span class="p">.</span><span class="n">A</span><span class="p">.</span>
<span class="p">...</span><span class="n">C</span><span class="p">.</span>    <span class="p">..</span><span class="mf">.3</span><span class="p">.</span>    <span class="p">...</span><span class="n">x</span><span class="p">.</span>
</pre></div>


</li>
</ul>
<h2 id="wall-punch">Wall Punch</h2>
<ul>
<li>
<p>Many die</p>
<div class="codehilite"><pre><span class="n">AAAAAAAAA</span>    <span class="mo">013565310</span>    <span class="n">AAxxxxxAA</span>
<span class="p">...</span><span class="n">BBB</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">..</span><span class="mf">.555</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">...</span><span class="n">xxx</span><span class="p">...</span>
<span class="p">...</span><span class="n">BBB</span><span class="p">...</span>    <span class="p">..</span><span class="mf">.333</span><span class="p">...</span>    <span class="p">...</span><span class="n">xBx</span><span class="p">...</span>
</pre></div>


</li>
<li>
<p>The B ant lives because it is only attacked by the 3 center A ants, and each of those ants are more occupied.  In turn, it participates in the death of the 3 center A ants.</p>
<div class="codehilite"><pre><span class="o">???</span><span class="n">AAA</span><span class="o">???</span>    <span class="o">???</span><span class="mi">565</span><span class="o">???</span>    <span class="n">AAxxxxxAA</span>
<span class="p">...</span><span class="o">???</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">...</span><span class="o">???</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">...</span><span class="n">xxx</span><span class="p">...</span>
<span class="p">...</span><span class="o">?</span><span class="n">B</span><span class="o">?</span><span class="p">...</span>    <span class="p">...</span><span class="o">?</span><span class="mi">3</span><span class="o">?</span><span class="p">...</span>    <span class="p">...</span><span class="n">xBx</span><span class="p">...</span>
</pre></div>


</li>
<li>
<p>The A ants on the end live because they are only attacked by 1 B ant which is much more occupied.</p>
<div class="codehilite"><pre><span class="o">?</span><span class="n">A</span><span class="o">???????</span>    <span class="o">?</span><span class="mi">1</span><span class="o">???????</span>    <span class="n">AAxxxxxAA</span>
<span class="p">...</span><span class="n">B</span><span class="o">??</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">..</span><span class="mf">.5</span><span class="o">??</span><span class="p">...</span> <span class="o">-&gt;</span> <span class="p">...</span><span class="n">xxx</span><span class="p">...</span>
<span class="p">...</span><span class="o">???</span><span class="p">...</span>    <span class="p">...</span><span class="o">???</span><span class="p">...</span>    <span class="p">...</span><span class="n">xBx</span><span class="p">...</span>
</pre></div>


</li>
</ul>
<p><em>Bob &amp; Bill vs. Roy &amp; Ralph</em>:</p>
<ul>
<li>The top and bottom ants are not within range of each other</li>
<li>
<p>1 of each dies</p>
<div class="codehilite"><pre><span class="cp">..B....    ..1....    ..B....</span>
<span class="cp">..B.R.. -&gt; ..2.2.. -&gt; ..x.x..</span>
<span class="cp">....R..    ....1..    ....R..</span>
</pre></div>


</li>
</ul>
<h1 id="math">Math</h1>
<p>For an ant location <img class='latex-inline math-true' alt='x' id='x' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAALVBMVEX///8AAACYmJiqqqpUVFTMzMx2dnaIiIgyMjJmZmbc3NxEREQQEBDu7u66urqYa2AGAAAALUlEQVQI12NgUHZVK2BgYsjgLGDgYtjOAAKiIIL1AO8FhhecDUwMDE9Uk3QZAIS/B2WJtI32AAAAAElFTkSuQmCC'>, define <img class='latex-inline math-true' alt='enemiesx' id='enemiesx' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFIAAAASBAMAAAA6WpTEAAAAMFBMVEX///8AAADu7u6IiIiYmJiqqqrc3NxERERmZmYyMjJ2dnYQEBDMzMy6urpUVFQiIiIMuohIAAABSUlEQVQoz4WSP0jDQBTGPy/nNTZRS2eHFEHExQzOEoSg+AdCaYpu56Q4SLCDa5Y6iEJnpw4t1EHQxckh6NJCBxcHi6SZxSGLu9f07Fbuwd33O+6Dd+/dA1TRz3ZP6QMJM3lTOxesTPJ8yn1nQt2xsGSK83VCN1IDZfZU6oPKyBoSDoFl16Htj80J0B0PlRBkowLNd0yR1f3ZtRGDBSTs73EjkrAYzjdYtYATrOKWpbMJKO51Gz3MtV3wb5QsCTNcS5iW4K7i4bJsCSdDTWTvCYuQFxz9wxA5B0ZEiuJ0/hSZo+4siRXjGLBYATUmoYPSCoZMEKFcb44qMh+JJSoyOGnSAMWqhF9sa7imeMbaOs9HokufeoMCbZh1H7qH+ruEFKdlDBx0fY/6B8AV4v3WFnCh7PxADor6j3JjEe9QhdnM5Ew9dfjKdht/HIFUBeWw3hgAAAAASUVORK5CYII='> as the set of enemy ants within the attack radius of <img class='latex-inline math-true' alt='x' id='x' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAALVBMVEX///8AAACYmJiqqqpUVFTMzMx2dnaIiIgyMjJmZmbc3NxEREQQEBDu7u66urqYa2AGAAAALUlEQVQI12NgUHZVK2BgYsjgLGDgYtjOAAKiIIL1AO8FhhecDUwMDE9Uk3QZAIS/B2WJtI32AAAAAElFTkSuQmCC'>.</p>
<p>For an ant location <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='>, <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='> lives if <img class='latex-inline math-true' alt='enemiesaenemiesbforallbinenemiesa' id='enemiesaenemies' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUMAAAASBAMAAAAzuKHSAAAAMFBMVEX///8AAAC6urpUVFRmZmbu7u6IiIiYmJiqqqrc3NxEREQyMjJ2dnYQEBDMzMwiIiISUvVYAAADRklEQVRIx52WQWjTYBTH/02TtGm7bAg77CIREUWmCxRFREvZacMhKbqBY7BCdRteLDpmFZTCNtrhJWMDRTeWi0O82OMOOxQZbF7GPIkXqWxOUJm9DOZF/PJ9X9o5sjbdB817Sd8v7998770UqL926DGLhtfOETm61JJ9DJKPUDdWilOzfSDWK7jtJdRl3WRCk4C/bqzfoCZi/h/rFaRcmjinGpN4gZkPh2aKxCvuFjOK7k3iyj5QeMc5O591tlxXVxVWuYCZQzOpRsW9zq3lTeK3/WCKc6eJ1KKs15VYhcUSsyEv++X89veV2L4YPIJLnCOJRDPMOkeORtP197nZ2U6tfialwJ0JJ3Y25qkWbbCVc5Fo9DJ8dGeUmKdSfMVtMG5n6sukA2tnUhUnMJoNjtIzyPm0agEDDzuBk1zVZrez0S4gcnFIwzkOCp8mTMopReRwLtNd7b5aMEZy+EEmwuqoBsEuZcWS4jtPzKYSd5rjQuHFNL2MTqUc1KHsSuRx/GQSx+84tegGKlMteEkGBgPFQkBnnAUd1/CAcN/5M64Bi9oCxoEFI2FALRA8vJaBeQUJgzs+U9bNFL2Mt5MGyRQuqmVHYvBvpV3cQIX0xHIuy8HmrFxmXEbSsYd7hLsbpbVYCw696bUlLuM3mMSE/eS/4JnjdCCUJoaezXwsqTqmTTle2ei5mCPRFWwq2U+cgz7DrzPuhqyRwoySb+4zvBasXD2OTShtuA220dOAobRgUeHOChK3hLJpnwVMsUiq/gTCk0alXfoucYluIDoU4kkcnIdPY5y/H8oGjtnNxiTWgPEIPaRdhBYs9bJ2aTKlYsBC6xR39vBYFotp+2zQjJTI7JjH1+fAmtPEwYtMohuIngA+Y4CDg/YsppywCKlM3weiRm9SA5b+YJ0MHWVDbUsjkiW42pWHmEWXxp0yZieFVXo5kCfb0g15eHsEeF2ZMwKbi24g2tPYymc5GBhOOtwuMDZG6R46lGvAyOQ0MkYx9HSI1KXhYQK38z8FVqPv6Pb9XPVtpaa8jG6hyGy/l0whPuZLjUoMuXJel8XMupdMKvs9c2hUIgPncDSJv9j4JPcQ6wefp8fkgVivYNJTqMtO05KVgX/z6C67CUyMxQAAAABJRU5ErkJggg=='>.</p>
<p>Similarly, for an ant location <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='>, <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='> dies if <img class='latex-inline math-true' alt='existsbinenemiesaenemiesageqenemiesb' id='existsbinenemie' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVAAAAASBAMAAAD/IZs5AAAAMFBMVEX///8AAADc3NxUVFRmZmbu7u4iIiK6urpERESIiIgyMjKqqqoQEBCYmJh2dnbMzMyeNVRKAAADmklEQVRIx52VS2gTYRDH/9nsdrNNN1sKwaNbKyK+WAx6Eg1VECzUbWlFEcoieuolIoio1Fx8IUKgIooUchckqKVoCdTepUELVuojqOChSCM+UEHrfI/d1BrcjXvIzMD8dv7ZmW8+oPnHqrHfZDPIEv8tNV9r6T85/gwIuQU/1kMJLcvN95W5uWgg57oymUyTQrcJ88aP46FE3OUmZa/IdaKBPqe6p7zQWqls0HnpdYUJfRd4n4VRHJ6btEOELgf1CwFHQg0nfDBd31NrwibChH4JvP3S5nmusrv2b6F/gKMBR0JbxbgamUwlvPMt/jfORW6937D3Ild7m4vYegJv1znVjfHPpeyKNKIbpE3SCCS9KEKVonTu+7k3SpGEMjBd51T37Hx3feShjZXxauKBEzgve5Cc5RFulfGNMj7M5qBTGb0ohB6ar5gLZ0YDx5wtoZzliDFZsfLA0Mu9wIngT81VhNBGtSRItQjUH9+3ORfPZLa7+/CUsJ/yJesxYP2cxozvHMVpbOxkEdTcOtwD1rl9Lqxi0Holr2WXXthtNem0ZPWicredI3sVL+lAOabRp/kRCDWPCKENavkg1SJQLZqOz6nuMK6zfmTEjN4pl5R4lV4sHHTQQNujLELiyiATegdfsVxo68I87J3oc6UTsw1HoRPKkEvjLtVrrVreMqHGFdn6BrV8kGoR2FIyvLrQNNgufSY7z6airZYsSkf3cBWY4ZGyYzU+QenANfDWS6F9bGhm8NB3TiJRIZIjXY9qloNO28jWWz90UR6mhrUkSLUIjLlxx+fU/imsIrtWTnA7NHQiUVgjnFRWS9u6x6Mn6KXDpLfj9iA/TFJoJ+ASdlmRzjv09eOkwt5k2mqVzsRxtI67/qEY8E+t07CWAFktAjchlgsO06DH17kql8Y0hjCH2GFbOC0lM19QqxWKtCNYpPWkTFkdFaRKwWFqs7WqmUf6rnSGMWeg12RvOmCnarRlNuHjZmCBFzS31hd+o1oC5LU8HGDrn3F0hW7H8+ec6xWL+PNkCT1QJ6RDwnpc/QOP5ss5qDZuvr5JM+QG68nqmYRKuTnpeFg7jpEKQ8xJmqhuGGPfbwHn/7pCG9USIK/VDXOs8DdnjUZZ+HpV2IPR7/oROf35Zu/6kcZcxCcvzGJ0ofK2pSHgrd9DT3ckoYnlXNPPL3HmqtGFWiJ3C5r9ogLcgv8TqvNRNoJYDUfO8d/CytxsNJBxvwG5VjzYebSE1wAAAABJRU5ErkJggg=='>.</p>
<h1 id="code">Code</h1>
<div class="tab_sync">
<div class="tab_content" title="Pseudo">

        // how to check if an ant dies
        for every ant:
            for each enemy in range of ant (using attackradius2):
                if (enemies(of ant) in range of ant) >= (enemies(of enemy) in range of enemy) then
                    the ant is marked dead (actual removal is done after all battles are resolved)
                    break out of enemy loop

</div>

<div class="tab_content" title="Python">

    :::python
            # we pre-calculate the number of enemies around each ant to make it faster

            # maps ants to nearby enemies
            nearby_enemies = {}
            for ant in self.current_ants.values():
                nearby_enemies[ant] = self.nearby_ants(ant.loc, self.attackradius, ant.owner)

            # determine which ants to kill
            ants_to_kill = []
            for ant in self.current_ants.values():
                # determine this ants weakness (1/power)
                weakness = len(nearby_enemies[ant])
                # an ant with no enemies nearby can't be attacked
                if weakness == 0:
                    continue
                # determine the most powerful nearby enemy
                min_enemy_weakness = min(len(nearby_enemies[enemy]) for enemy in nearby_enemies[ant])
                # ant dies if it is weak as or weaker than an enemy weakness
                if min_enemy_weakness <= weakness:
                    ants_to_kill.append(ant)

</div>

</div>
<!--</MarkdownReplacement>-->

<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>