<?php

$title="Ants Tutorial Step 3";
require_once('header.php');

?>

<!--<MarkdownReplacement with="competition-Tutorial-Step-3.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="step-3-not-blocking-hills">Step 3: Not Blocking Hills</h2>
<div class="toc">

*   <a href="ants_tutorial.php">Setting Up</a>
*   <a href="ants_tutorial_step_1.php">Step 1: Avoiding Collisions</a>
*   <a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a>
*   <a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a>
*   <a href="ants_tutorial_step_4.php">Step 4: Explore the Map</a>
*   <a href="ants_tutorial_step_5.php">Step 5: Attack the Enemy Hills</a>

</div>

<h3 id="the-plan">The Plan</h3>
<p>We need to make sure if our ant spawns we move it off the hill right away so more ants in the hive can spawn.  This will only need to be done if the ant hasn't been given any other order, so we'll put it after the food gathering code.</p>
<p>Also, if for some reason food spawned such that an ant right next to the hill wanted to move back onto the hill to go get it, we are going to prevent that.</p>
<h3 id="the-implementation">The Implementation</h3>
<div class="tab_sync">
<div class="tab_content" title="Python">

Let's first prevent stepping on our own hill.  We are already tracking information about all moves.  Let's just add a dummy order so that the move helper functions think it is always occupied.

Next, at the end of our `do_turn` function, we'll check if an ant is still on the hill and have it move one of the four directions.

</div>

<div class="tab_content" title="Java">

Let's first prevent stepping on our own hill.  We are already tracking information about reserved tiles.  Let's just add some dummy entries so that `doMoveDirection` will think it is always occupied.

Next, at the end of our `doTurn` function, we'll check if an ant is still on a hill and have it move any of the four directions.

</div>

</div>

<h3 id="the-code">The Code</h3>
<div class="tab_sync">
<div class="tab_content" title="Python">

Add this code just before the food gathering section:

<div class="codehilite"><pre>    <span class="c"># prevent stepping on own hill</span>
    <span class="k">for</span> <span class="n">hill_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">my_hills</span><span class="p">():</span>
        <span class="n">orders</span><span class="p">[</span><span class="n">hill_loc</span><span class="p">]</span> <span class="o">=</span> <span class="bp">None</span>
</pre></div>


The dummy entry doesn't need a from location, so we just set the value to `None`.

Add this code after the food gathering section:


<div class="codehilite"><pre>    <span class="c"># unblock own hill</span>
    <span class="k">for</span> <span class="n">hill_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">my_hills</span><span class="p">():</span>
        <span class="k">if</span> <span class="n">hill_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">my_ants</span><span class="p">()</span> <span class="ow">and</span> <span class="n">hill_loc</span> <span class="ow">not</span> <span class="ow">in</span> <span class="n">orders</span><span class="o">.</span><span class="n">values</span><span class="p">():</span>
            <span class="k">for</span> <span class="n">direction</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;s&#39;</span><span class="p">,</span><span class="s">&#39;e&#39;</span><span class="p">,</span><span class="s">&#39;w&#39;</span><span class="p">,</span><span class="s">&#39;n&#39;</span><span class="p">):</span>
                <span class="k">if</span> <span class="n">do_move_direction</span><span class="p">(</span><span class="n">hill_loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">):</span>
                    <span class="k">break</span>
</pre></div>


Here we check if an ant is on our hill, and if so, we loop through all four directions trying to get it off.  Once we find a direction that works, we stop trying the other ones by using the `break` statement.  It's a good thing our helper function returns some useful information!

* `ants.my_hills` returns us a list of locations where our hills are located.  Remember that a location is a tuple of `(row, col)`.

</div>

<div class="tab_content" title="Java">

Add this to the top of the `doTurn` function (just after the foodTargets declaration):


<div class="codehilite"><pre>        <span class="c1">// prevent stepping on own hill</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Tile</span> <span class="n">myHill</span> <span class="o">:</span> <span class="n">ants</span><span class="o">.</span><span class="na">getMyHills</span><span class="o">())</span> <span class="o">{</span>
            <span class="n">orders</span><span class="o">.</span><span class="na">put</span><span class="o">(</span><span class="n">myHill</span><span class="o">,</span> <span class="kc">null</span><span class="o">);</span>
        <span class="o">}</span>
</pre></div>


This will loop through all our hills and add them to the set of reserved tiles.  No ant will try to move onto the our hill now.

Add this after the loops to find close food:


<div class="codehilite"><pre>        <span class="c1">// unblock hills</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Tile</span> <span class="n">myHill</span> <span class="o">:</span> <span class="n">ants</span><span class="o">.</span><span class="na">getMyHills</span><span class="o">())</span> <span class="o">{</span>
            <span class="k">if</span> <span class="o">(</span><span class="n">ants</span><span class="o">.</span><span class="na">getMyAnts</span><span class="o">().</span><span class="na">contains</span><span class="o">(</span><span class="n">myHill</span><span class="o">)</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="n">orders</span><span class="o">.</span><span class="na">containsValue</span><span class="o">(</span><span class="n">myHill</span><span class="o">))</span> <span class="o">{</span>
                <span class="k">for</span> <span class="o">(</span><span class="n">Aim</span> <span class="n">direction</span> <span class="o">:</span> <span class="n">Aim</span><span class="o">.</span><span class="na">values</span><span class="o">())</span> <span class="o">{</span>
                    <span class="k">if</span> <span class="o">(</span><span class="n">doMoveDirection</span><span class="o">(</span><span class="n">myHill</span><span class="o">,</span> <span class="n">direction</span><span class="o">))</span> <span class="o">{</span>
                        <span class="k">break</span><span class="o">;</span>
                    <span class="o">}</span>
                <span class="o">}</span>
            <span class="o">}</span>
        <span class="o">}</span>
</pre></div>


This will loop through all our hill locations.  If there is an ant there that does not have an order, we will try and send it one of the four directions.

* method `ants.getMyHills()` returns a Set of Tile objects containing our hill locations

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
<span class="n">turn</span>   <span class="mi">60</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">7</span><span class="p">,</span><span class="mi">6</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">7</span>     <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="n">score</span> <span class="mi">1</span> <span class="mi">1</span>
<span class="n">status</span> <span class="n">survived</span> <span class="n">survived</span>
<span class="n">playerturns</span> <span class="mi">60</span> <span class="mi">60</span>
</pre></div>


<p>Here is the replay:</p>
<div class="codehilite"><pre><span class="c"># [ { &quot;embedded&quot;: true, &quot;game&quot;: &quot;3 - Not Blocking Hills&quot; }, 600, 600, { &quot;speedFactor&quot;: 0, &quot;speedFastest&quot;: 2, &quot;speedSlowest&quot;: 2, &quot;zoom&quot;: 1 }, &quot;example_games/tutorial.3.replay&quot; ]</span>
</pre></div>


<p>Good!  All of our ants got out of the hive.  But they just stop doing stuff after they can't see any more food.  If you click on the vision button on the left side of the map, you can see that the remaining food is outside the ants' vision.  Let's fix that next.</p>
<h3 id="next">Next</h3>
<p>On to <a href="ants_tutorial_step_4.php">Step 4: Exploring the Map</a></p>
<!--</MarkdownReplacement>-->

<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>