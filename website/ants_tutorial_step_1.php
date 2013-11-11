<?php

$title="Ants Tutorial Step 1";
require_once('header.php');

?>

<!--<MarkdownReplacement with="competition-Tutorial-Step-1.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="step-1-avoiding-collisions">Step 1: Avoiding collisions</h2>
<div class="toc">

*   <a href="ants_tutorial.php">Setting Up</a>
*   <a href="ants_tutorial_step_1.php">Step 1: Avoiding Collisions</a>
*   <a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a>
*   <a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a>
*   <a href="ants_tutorial_step_4.php">Step 4: Explore the Map</a>
*   <a href="ants_tutorial_step_5.php">Step 5: Attack the Enemy Hills</a>

</div>

<h3 id="the-plan">The Plan</h3>
<p>In order to prevent collisions, we will need to do a few things:</p>
<ul>
<li>prevent ants from moving onto other ants</li>
<li>prevent 2 ants from moving to the same destination</li>
<li>track information about where all our ants are going</li>
</ul>
<h3 id="the-implementation">The Implementation</h3>
<div class="tab_sync">
<div class="tab_content" title="Python">

To track information about where ants are moving, we are going to use a dictionary.  It is a data structure that will store locations, and then allow us to check if a location has already been stored.  Each key of the dictionary will be a location we are moving to and each value will be the ant location that is moving to the new location.  A location will be a tuple of values consisting of the row and column of the map location.  We can then check the dictionary before making a move to ensure we don't move 2 ants to the same spot.  Every time we move an ant, we need to be sure to update the list.

This check will come in handy later in the tutorial, so we will make a function to attempt moves and check to make sure the move is to an empty location.  It will return a boolean (true or false) to let us know if the move worked.

</div>

<div class="tab_content" title="Java">

To track information about where ants are moving, we are going to use a HashMap.  It is a data structure that will store locations, and then allow us to check if a location has already been stored.  Each key and value of the HashMap will be a Tile object.  A Tile object is the row and column of a location on the map.  The key will be the new location to move to and the value will be the location of the ant moving to the new location.  We can then check the HashMap before making a move to ensure we don't move 2 ants to the same spot.  Every time we move an ant, we need to be sure to update the HashMap.

This check will come in handy later in the tutorial, so we will make a function to attempt moves and check to make sure the move is to an empty location.  It will return a boolean (true or false) to let us know if the move worked.

</div>

</div>

<h3 id="the-code">The Code</h3>
<p>We'll trim down the starter bots comments and put the new code in:</p>
<div class="tab_sync">
<div class="tab_content" title="Python">

<div class="codehilite"><pre>    <span class="k">def</span> <span class="nf">do_turn</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">ants</span><span class="p">):</span>
        <span class="c"># track all moves, prevent collisions</span>
        <span class="n">orders</span> <span class="o">=</span> <span class="p">{}</span>
        <span class="k">def</span> <span class="nf">do_move_direction</span><span class="p">(</span><span class="n">loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">):</span>
            <span class="n">new_loc</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="n">destination</span><span class="p">(</span><span class="n">loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">)</span>
            <span class="k">if</span> <span class="p">(</span><span class="n">ants</span><span class="o">.</span><span class="n">unoccupied</span><span class="p">(</span><span class="n">new_loc</span><span class="p">)</span> <span class="ow">and</span> <span class="n">new_loc</span> <span class="ow">not</span> <span class="ow">in</span> <span class="n">orders</span><span class="p">):</span>
                <span class="n">ants</span><span class="o">.</span><span class="n">issue_order</span><span class="p">((</span><span class="n">loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">))</span>
                <span class="n">orders</span><span class="p">[</span><span class="n">new_loc</span><span class="p">]</span> <span class="o">=</span> <span class="n">loc</span>
                <span class="k">return</span> <span class="bp">True</span>
            <span class="k">else</span><span class="p">:</span>
                <span class="k">return</span> <span class="bp">False</span>

        <span class="c"># default move</span>
        <span class="k">for</span> <span class="n">ant_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">my_ants</span><span class="p">():</span>
            <span class="n">directions</span> <span class="o">=</span> <span class="p">(</span><span class="s">&#39;n&#39;</span><span class="p">,</span><span class="s">&#39;e&#39;</span><span class="p">,</span><span class="s">&#39;s&#39;</span><span class="p">,</span><span class="s">&#39;w&#39;</span><span class="p">)</span>
            <span class="k">for</span> <span class="n">direction</span> <span class="ow">in</span> <span class="n">directions</span><span class="p">:</span>
                <span class="k">if</span> <span class="n">do_move_direction</span><span class="p">(</span><span class="n">ant_loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">):</span>
                    <span class="k">break</span>
</pre></div>


*(Note: Make sure you get the indentation correct.  In Python, indentation determines the code blocks or scope, so it has to be correct.)*

The `do_move_direction` takes an ant location (a tuple of (row, col) ) and a direction ( 'n', 'e', 's' or 'w' ) and tries to perform the move.  This function is located inside a class method (which is also a function) and is okay to do in python.  We are using some predefined functions from the starter bot to help us:

* `ants.destination` takes a location and a direction and returns the destination location for us.  It takes care of the map wrapping around so we don't have to worry about it.

* `ants.unoccupied` takes a location and let's us know if we can move there.  This is better than the previous `ants.passable` because it will not allow us to step on food or other ants.

The `orders` dictionary is used to track what moves we have issued.  In the if statement we have `new_loc not in orders` which will check the dictionary keys for us and help prevent collisions.
</div>

<div class="tab_content" title="Java">

We'll trim down the starter bots comments and put the new code in:


<div class="codehilite"><pre>    <span class="kd">private</span> <span class="n">Map</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">,</span> <span class="n">Tile</span><span class="o">&gt;</span> <span class="n">orders</span> <span class="o">=</span> <span class="k">new</span> <span class="n">HashMap</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">,</span> <span class="n">Tile</span><span class="o">&gt;();</span>

    <span class="kd">private</span> <span class="kt">boolean</span> <span class="nf">doMoveDirection</span><span class="o">(</span><span class="n">Tile</span> <span class="n">antLoc</span><span class="o">,</span> <span class="n">Aim</span> <span class="n">direction</span><span class="o">)</span> <span class="o">{</span>
        <span class="n">Ants</span> <span class="n">ants</span> <span class="o">=</span> <span class="n">getAnts</span><span class="o">();</span>
        <span class="c1">// Track all moves, prevent collisions</span>
        <span class="n">Tile</span> <span class="n">newLoc</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="na">getTile</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">direction</span><span class="o">);</span>
        <span class="k">if</span> <span class="o">(</span><span class="n">ants</span><span class="o">.</span><span class="na">getIlk</span><span class="o">(</span><span class="n">newLoc</span><span class="o">).</span><span class="na">isUnoccupied</span><span class="o">()</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="n">orders</span><span class="o">.</span><span class="na">containsKey</span><span class="o">(</span><span class="n">newLoc</span><span class="o">))</span> <span class="o">{</span>
            <span class="n">ants</span><span class="o">.</span><span class="na">issueOrder</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">direction</span><span class="o">);</span>
            <span class="n">orders</span><span class="o">.</span><span class="na">put</span><span class="o">(</span><span class="n">newLoc</span><span class="o">,</span> <span class="n">antLoc</span><span class="o">);</span>
            <span class="k">return</span> <span class="kc">true</span><span class="o">;</span>
        <span class="o">}</span> <span class="k">else</span> <span class="o">{</span>
            <span class="k">return</span> <span class="kc">false</span><span class="o">;</span>
        <span class="o">}</span>
    <span class="o">}</span>

    <span class="nd">@Override</span>
    <span class="kd">public</span> <span class="kt">void</span> <span class="nf">doTurn</span><span class="o">()</span> <span class="o">{</span>
        <span class="n">Ants</span> <span class="n">ants</span> <span class="o">=</span> <span class="n">getAnts</span><span class="o">();</span>
        <span class="n">orders</span><span class="o">.</span><span class="na">clear</span><span class="o">();</span>

        <span class="c1">//  default move</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Tile</span> <span class="n">myAnt</span> <span class="o">:</span> <span class="n">ants</span><span class="o">.</span><span class="na">getMyAnts</span><span class="o">())</span> <span class="o">{</span>
            <span class="k">for</span> <span class="o">(</span><span class="n">Aim</span> <span class="n">direction</span> <span class="o">:</span> <span class="n">Aim</span><span class="o">.</span><span class="na">values</span><span class="o">())</span> <span class="o">{</span>
                <span class="k">if</span> <span class="o">(</span><span class="n">doMoveDirection</span><span class="o">(</span><span class="n">myAnt</span><span class="o">,</span> <span class="n">direction</span><span class="o">))</span> <span class="o">{</span>
                    <span class="k">break</span><span class="o">;</span>
                <span class="o">}</span>
            <span class="o">}</span>
        <span class="o">}</span>
    <span class="o">}</span>
</pre></div>


The `doMoveDirection` function takes an ant location (a Tile object) and a direction (an Aim object of N, E, S or W) and tries to perform the move.  This function is located outside the `doTurn` function, so our reserved tiles HashMap is at the class level and we clear it for each turn.  We are using some predefined functions from the starter bot to help us:

* `ants.getTile(Tile, Aim)` takes a location (Tile object) and a direction (Aim object) and returns the destination location (Tile object) for us.  It takes care of the map wrapping around so we don't have to worry about it.

* `ants.getIlk(Tile)` takes a location (Tile object) and returns the Ilk (a fancy word for type or kind).  We then call the `isUnoccupied()` function of the Ilk object to see if it is free to move to.

* `Ilk.isUnoccupied` takes a location and let's us know if we can move there.  This is better than the previous `Ilk.isPassable` because it will not allow us to step on food or other ants.

The `orders` HashMap is used to track what moves we have issued.  In the if statement we have `!orders.containsKey(newLoc)` which will check the HashMap for us and help prevent collisions.
</div>

</div>

<h3 id="the-results">The Results</h3>
<p>Let's run the bot again and see how we do.</p>
<div class="codehilite"><pre><span class="nl">C:</span><span class="err">\</span><span class="n">aichallenge</span><span class="o">&gt;</span><span class="n">tutorial</span><span class="p">.</span><span class="n">cmd</span>
<span class="n">running</span> <span class="k">for</span> <span class="mi">60</span> <span class="n">turns</span>
                  <span class="n">ant_count</span> <span class="n">c_turns</span> <span class="n">climb</span><span class="o">?</span> <span class="n">cutoff</span> <span class="n">food</span> <span class="n">r_turn</span> <span class="n">ranking_bots</span> <span class="n">s_alive</span> <span class="n">s_hills</span> <span class="n">score</span>  <span class="n">w_turn</span> <span class="n">winning</span>
<span class="n">turn</span>    <span class="mi">0</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">18</span>    <span class="mi">0</span>        <span class="n">None</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">0</span>     <span class="n">None</span>
<span class="n">turn</span>    <span class="mi">1</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">2</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="p">...</span>
<span class="n">turn</span>   <span class="mi">60</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">5</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">12</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="n">score</span> <span class="mi">1</span> <span class="mi">1</span>
<span class="n">status</span> <span class="n">survived</span> <span class="n">survived</span>
<span class="n">playerturns</span> <span class="mi">60</span> <span class="mi">60</span>
</pre></div>


<p>Here is the replay:</p>
<div class="codehilite"><pre><span class="c"># [ { &quot;embedded&quot;: true, &quot;game&quot;: &quot;1 - Avoiding collisions&quot; }, 600, 600, { &quot;speedFactor&quot;: 0, &quot;speedFastest&quot;: 2, &quot;speedSlowest&quot;: 2, &quot;zoom&quot;: 1 }, &quot;example_games/tutorial.1.replay&quot; ]</span>
</pre></div>


<p>Better, but still not good.  One lone ant got out and got to fight with HunterBot.  We didn't suicide, and that's an improvement.  Plus, we created a helper function that will come in handy later.</p>
<p>If your bot's ants oscillated behind their barrier instead, it is probably due to the ordering of the ants in your loop.  If the NW ant moves first it moves to the North of the SE ant, which can then only move East, South or West.  Otherwise if the SE ant moves first it moves to the East of the NW ant, which can then only move South or West.</p>
<h3 id="next">Next</h3>
<p>On to <a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a></p>
<!--</MarkdownReplacement>-->

<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>