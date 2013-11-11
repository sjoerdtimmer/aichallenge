<?php

$title="Ants Tutorial Step 2";
require_once('header.php');

?>

<!--<MarkdownReplacement with="competition-Tutorial-Step-2.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="step-2-gathering-food">Step 2: Gathering Food</h2>
<div class="toc">

*   <a href="ants_tutorial.php">Setting Up</a>
*   <a href="ants_tutorial_step_1.php">Step 1: Avoiding Collisions</a>
*   <a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a>
*   <a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a>
*   <a href="ants_tutorial_step_4.php">Step 4: Explore the Map</a>
*   <a href="ants_tutorial_step_5.php">Step 5: Attack the Enemy Hills</a>

</div>

<h3 id="the-plan">The Plan</h3>
<p>We need more than 2 ants to win this game and there's food right next to the starting ant!  Let's try and gather it.  We'll need to move an ant right next to a food item to gather it.  We also want to be smart about it.  Did you notice that HunterBot sent all his ants after one food item in the last game?  That seems like it could be inefficient.</p>
<p>We are going to implement something similar to a priority queue.  We'll make a list of every ant we have, and then see how far it is from every food item.  We'll then sort the list and send each ant after the closest food, but only one ant per food item.  The other ants will be free to do other important things.  We'll also get rid of the stupid default move that came with the starter bot.</p>
<h3 id="the-implementation">The Implementation</h3>
<div class="tab_sync">
<div class="tab_content" title="Python">

To track information about which food item is already being gathered by an ant, we'll need another dictionary.  It will store the location of the target food as the key, and the location of the ant that is gathering the food as the value.  We can then check the target keys to make sure we don't send two ants to the same food.  We will create another helper function to make a slightly different type of move.  Instead of an ant location and a direction, we will give it an ant location and a target location, and the function will figure out the direction for us.

</div>

<div class="tab_content" title="Java">

To track information about which food item is already being gathered by an ant, we'll need another data structure to store location information.  We'll use another HashMap, so we can store the location of the target food as the key, and the location of the ant that is gathering the food as the value.  We can then check the target keys to make sure we don't send two ants to the same food.  We will create another helper function to make a slightly different type of move.  Instead of an ant location and a direction, we will give it an ant location and a target location, and the function will figure out the direction for us.  The new Route class will store a start and end location, so we can put pair into other data structures.  The ArrayList data structure will help us sort the list of unique routes by distance.

</div>

</div>

<h3 id="the-code">The Code</h3>
<div class="tab_sync">
<div class="tab_content" title="Python">

Create the following function after the `do_move_direction` function:


<div class="codehilite"><pre>        <span class="n">targets</span> <span class="o">=</span> <span class="p">{}</span>
        <span class="k">def</span> <span class="nf">do_move_location</span><span class="p">(</span><span class="n">loc</span><span class="p">,</span> <span class="n">dest</span><span class="p">):</span>
            <span class="n">directions</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="n">direction</span><span class="p">(</span><span class="n">loc</span><span class="p">,</span> <span class="n">dest</span><span class="p">)</span>
            <span class="k">for</span> <span class="n">direction</span> <span class="ow">in</span> <span class="n">directions</span><span class="p">:</span>
                <span class="k">if</span> <span class="n">do_move_direction</span><span class="p">(</span><span class="n">loc</span><span class="p">,</span> <span class="n">direction</span><span class="p">):</span>
                    <span class="n">targets</span><span class="p">[</span><span class="n">dest</span><span class="p">]</span> <span class="o">=</span> <span class="n">loc</span>
                    <span class="k">return</span> <span class="bp">True</span>
            <span class="k">return</span> <span class="bp">False</span>
</pre></div>


Make sure this function has the same indentation as the `do_move_direction` function.  The `targets` dictionary tracks our food targets and ants.  We are using another starter bot function to help us:

* `ants.direction` takes a location and a destination and returns a list of the closest direction "as the crow flies".  If the target is up and to the left, it will return `['n', 'w']` and we should then try and move our ant one of the two directions.  If the target is directly down, it will return `['s']`, which is a list of one item.

Now replace the default move with this:


<div class="codehilite"><pre>        <span class="c"># find close food</span>
        <span class="n">ant_dist</span> <span class="o">=</span> <span class="p">[]</span>
        <span class="k">for</span> <span class="n">food_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">food</span><span class="p">():</span>
            <span class="k">for</span> <span class="n">ant_loc</span> <span class="ow">in</span> <span class="n">ants</span><span class="o">.</span><span class="n">my_ants</span><span class="p">():</span>
                <span class="n">dist</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="n">distance</span><span class="p">(</span><span class="n">ant_loc</span><span class="p">,</span> <span class="n">food_loc</span><span class="p">)</span>
                <span class="n">ant_dist</span><span class="o">.</span><span class="n">append</span><span class="p">((</span><span class="n">dist</span><span class="p">,</span> <span class="n">ant_loc</span><span class="p">,</span> <span class="n">food_loc</span><span class="p">))</span>
        <span class="n">ant_dist</span><span class="o">.</span><span class="n">sort</span><span class="p">()</span>
        <span class="k">for</span> <span class="n">dist</span><span class="p">,</span> <span class="n">ant_loc</span><span class="p">,</span> <span class="n">food_loc</span> <span class="ow">in</span> <span class="n">ant_dist</span><span class="p">:</span>
            <span class="k">if</span> <span class="n">food_loc</span> <span class="ow">not</span> <span class="ow">in</span> <span class="n">targets</span> <span class="ow">and</span> <span class="n">ant_loc</span> <span class="ow">not</span> <span class="ow">in</span> <span class="n">targets</span><span class="o">.</span><span class="n">values</span><span class="p">():</span>
                <span class="n">do_move_location</span><span class="p">(</span><span class="n">ant_loc</span><span class="p">,</span> <span class="n">food_loc</span><span class="p">)</span>
</pre></div>


Here we have a list, `ant_dist`, which will store every ant to food combination and the distance as a tuple of `(dist, ant_loc, food_loc)`.  The list is built by a nested loop structure to give us every combination.  Next, we sort the list.  Python lists come with some handy functions to do the sorting for us.  To order a tuple, python will compare the first values of each tuple first, then if they are the same, move on to the second value and so forth.  This is why we stored the distance as the first value, to make sure the shortest distances are first in the list.

Next we loop through the sorted list and check to see if we have any free ants that can gather food.  The `food_loc not in targets` check to see if a food item already has an ant gathering it.  The `ant_loc not in targets.values()` checks to make sure the ant hasn't already been given a task.  If an ant is found, we call `do_move_location` and all the direction and collision stuff is already taken care of for us.

</div>

<div class="tab_content" title="Java">

Create the following class "Route" in a new file called "Route.java":


<div class="codehilite"><pre><span class="cm">/**</span>
<span class="cm"> * Represents a route from one tile to another.</span>
<span class="cm"> */</span>
<span class="kd">public</span> <span class="kd">class</span> <span class="nc">Route</span> <span class="kd">implements</span> <span class="n">Comparable</span><span class="o">&lt;</span><span class="n">Route</span><span class="o">&gt;</span> <span class="o">{</span>
    <span class="kd">private</span> <span class="kd">final</span> <span class="n">Tile</span> <span class="n">start</span><span class="o">;</span>

    <span class="kd">private</span> <span class="kd">final</span> <span class="n">Tile</span> <span class="n">end</span><span class="o">;</span>

    <span class="kd">private</span> <span class="kd">final</span> <span class="kt">int</span> <span class="n">distance</span><span class="o">;</span>

    <span class="kd">public</span> <span class="nf">Route</span><span class="o">(</span><span class="n">Tile</span> <span class="n">start</span><span class="o">,</span> <span class="n">Tile</span> <span class="n">end</span><span class="o">,</span> <span class="kt">int</span> <span class="n">distance</span><span class="o">)</span> <span class="o">{</span>
        <span class="k">this</span><span class="o">.</span><span class="na">start</span> <span class="o">=</span> <span class="n">start</span><span class="o">;</span>
        <span class="k">this</span><span class="o">.</span><span class="na">end</span> <span class="o">=</span> <span class="n">end</span><span class="o">;</span>
        <span class="k">this</span><span class="o">.</span><span class="na">distance</span> <span class="o">=</span> <span class="n">distance</span><span class="o">;</span>
    <span class="o">}</span>

    <span class="kd">public</span> <span class="n">Tile</span> <span class="nf">getStart</span><span class="o">()</span> <span class="o">{</span>
        <span class="k">return</span> <span class="n">start</span><span class="o">;</span>
    <span class="o">}</span>

    <span class="kd">public</span> <span class="n">Tile</span> <span class="nf">getEnd</span><span class="o">()</span> <span class="o">{</span>
        <span class="k">return</span> <span class="n">end</span><span class="o">;</span>
    <span class="o">}</span>

    <span class="kd">public</span> <span class="kt">int</span> <span class="nf">getDistance</span><span class="o">()</span> <span class="o">{</span>
        <span class="k">return</span> <span class="n">distance</span><span class="o">;</span>
    <span class="o">}</span>

    <span class="nd">@Override</span>
    <span class="kd">public</span> <span class="kt">int</span> <span class="nf">compareTo</span><span class="o">(</span><span class="n">Route</span> <span class="n">route</span><span class="o">)</span> <span class="o">{</span>
        <span class="k">return</span> <span class="n">distance</span> <span class="o">-</span> <span class="n">route</span><span class="o">.</span><span class="na">distance</span><span class="o">;</span>
    <span class="o">}</span>

    <span class="nd">@Override</span>
    <span class="kd">public</span> <span class="kt">int</span> <span class="nf">hashCode</span><span class="o">()</span> <span class="o">{</span>
        <span class="k">return</span> <span class="n">start</span><span class="o">.</span><span class="na">hashCode</span><span class="o">()</span> <span class="o">*</span> <span class="n">Ants</span><span class="o">.</span><span class="na">MAX_MAP_SIZE</span> <span class="o">*</span> <span class="n">Ants</span><span class="o">.</span><span class="na">MAX_MAP_SIZE</span> <span class="o">+</span> <span class="n">end</span><span class="o">.</span><span class="na">hashCode</span><span class="o">();</span>
    <span class="o">}</span>

    <span class="nd">@Override</span>
    <span class="kd">public</span> <span class="kt">boolean</span> <span class="nf">equals</span><span class="o">(</span><span class="n">Object</span> <span class="n">o</span><span class="o">)</span> <span class="o">{</span>
        <span class="kt">boolean</span> <span class="n">result</span> <span class="o">=</span> <span class="kc">false</span><span class="o">;</span>
        <span class="k">if</span> <span class="o">(</span><span class="n">o</span> <span class="k">instanceof</span> <span class="n">Route</span><span class="o">)</span> <span class="o">{</span>
            <span class="n">Route</span> <span class="n">route</span> <span class="o">=</span> <span class="o">(</span><span class="n">Route</span><span class="o">)</span><span class="n">o</span><span class="o">;</span>
            <span class="n">result</span> <span class="o">=</span> <span class="n">start</span><span class="o">.</span><span class="na">equals</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">start</span><span class="o">)</span> <span class="o">&amp;&amp;</span> <span class="n">end</span><span class="o">.</span><span class="na">equals</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">end</span><span class="o">);</span>
        <span class="o">}</span>
        <span class="k">return</span> <span class="n">result</span><span class="o">;</span>
    <span class="o">}</span>
<span class="o">}</span>
</pre></div>


This is a basic class the implements the idea of a tuple or pair.  We add some getter functions (`getStart`, `getEnd`) and some function to make sure it behaves nicely for sorting and using inside other data structures (`compareTo`, `hashCode`, `equals`).

Add the following new function to the MyBot.java file after the `doMoveDirection` function:


<div class="codehilite"><pre>    <span class="kd">private</span> <span class="kt">boolean</span> <span class="nf">doMoveLocation</span><span class="o">(</span><span class="n">Tile</span> <span class="n">antLoc</span><span class="o">,</span> <span class="n">Tile</span> <span class="n">destLoc</span><span class="o">)</span> <span class="o">{</span>
        <span class="n">Ants</span> <span class="n">ants</span> <span class="o">=</span> <span class="n">getAnts</span><span class="o">();</span>
        <span class="c1">// Track targets to prevent 2 ants to the same location</span>
        <span class="n">List</span><span class="o">&lt;</span><span class="n">Aim</span><span class="o">&gt;</span> <span class="n">directions</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="na">getDirections</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">destLoc</span><span class="o">);</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Aim</span> <span class="n">direction</span> <span class="o">:</span> <span class="n">directions</span><span class="o">)</span> <span class="o">{</span>
            <span class="k">if</span> <span class="o">(</span><span class="n">doMoveDirection</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">direction</span><span class="o">))</span> <span class="o">{</span>
                <span class="k">return</span> <span class="kc">true</span><span class="o">;</span>
            <span class="o">}</span>
        <span class="o">}</span>
        <span class="k">return</span> <span class="kc">false</span><span class="o">;</span>
    <span class="o">}</span>
</pre></div>


This function will take an ant and a target location, then attempt to do the move.  It is using the `doMoveDirection` from the last step, so it will already make sure we don't step on our own ants.

Replace the default move with the following code:


<div class="codehilite"><pre>    <span class="nd">@Override</span>
    <span class="kd">public</span> <span class="kt">void</span> <span class="nf">doTurn</span><span class="o">()</span> <span class="o">{</span>
        <span class="n">Ants</span> <span class="n">ants</span> <span class="o">=</span> <span class="n">getAnts</span><span class="o">();</span>
        <span class="n">orders</span><span class="o">.</span><span class="na">clear</span><span class="o">();</span>
        <span class="n">Map</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">,</span> <span class="n">Tile</span><span class="o">&gt;</span> <span class="n">foodTargets</span> <span class="o">=</span> <span class="k">new</span> <span class="n">HashMap</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">,</span> <span class="n">Tile</span><span class="o">&gt;();</span>

        <span class="c1">// find close food</span>
        <span class="n">List</span><span class="o">&lt;</span><span class="n">Route</span><span class="o">&gt;</span> <span class="n">foodRoutes</span> <span class="o">=</span> <span class="k">new</span> <span class="n">ArrayList</span><span class="o">&lt;</span><span class="n">Route</span><span class="o">&gt;();</span>
        <span class="n">TreeSet</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">&gt;</span> <span class="n">sortedFood</span> <span class="o">=</span> <span class="k">new</span> <span class="n">TreeSet</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">&gt;(</span><span class="n">ants</span><span class="o">.</span><span class="na">getFoodTiles</span><span class="o">());</span>
        <span class="n">TreeSet</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">&gt;</span> <span class="n">sortedAnts</span> <span class="o">=</span> <span class="k">new</span> <span class="n">TreeSet</span><span class="o">&lt;</span><span class="n">Tile</span><span class="o">&gt;(</span><span class="n">ants</span><span class="o">.</span><span class="na">getMyAnts</span><span class="o">());</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Tile</span> <span class="n">foodLoc</span> <span class="o">:</span> <span class="n">sortedFood</span><span class="o">)</span> <span class="o">{</span>
            <span class="k">for</span> <span class="o">(</span><span class="n">Tile</span> <span class="n">antLoc</span> <span class="o">:</span> <span class="n">sortedAnts</span><span class="o">)</span> <span class="o">{</span>
                <span class="kt">int</span> <span class="n">distance</span> <span class="o">=</span> <span class="n">ants</span><span class="o">.</span><span class="na">getDistance</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">foodLoc</span><span class="o">);</span>
                <span class="n">Route</span> <span class="n">route</span> <span class="o">=</span> <span class="k">new</span> <span class="n">Route</span><span class="o">(</span><span class="n">antLoc</span><span class="o">,</span> <span class="n">foodLoc</span><span class="o">,</span> <span class="n">distance</span><span class="o">);</span>
                <span class="n">foodRoutes</span><span class="o">.</span><span class="na">add</span><span class="o">(</span><span class="n">route</span><span class="o">);</span>
            <span class="o">}</span>
        <span class="o">}</span>
        <span class="n">Collections</span><span class="o">.</span><span class="na">sort</span><span class="o">(</span><span class="n">foodRoutes</span><span class="o">);</span>
        <span class="k">for</span> <span class="o">(</span><span class="n">Route</span> <span class="n">route</span> <span class="o">:</span> <span class="n">foodRoutes</span><span class="o">)</span> <span class="o">{</span>
            <span class="k">if</span> <span class="o">(!</span><span class="n">foodTargets</span><span class="o">.</span><span class="na">containsKey</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">getEnd</span><span class="o">())</span>
                    <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="n">foodTargets</span><span class="o">.</span><span class="na">containsValue</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">getStart</span><span class="o">())</span>
                    <span class="o">&amp;&amp;</span> <span class="n">doMoveLocation</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">getStart</span><span class="o">(),</span> <span class="n">route</span><span class="o">.</span><span class="na">getEnd</span><span class="o">()))</span> <span class="o">{</span>
                <span class="n">foodTargets</span><span class="o">.</span><span class="na">put</span><span class="o">(</span><span class="n">route</span><span class="o">.</span><span class="na">getEnd</span><span class="o">(),</span> <span class="n">route</span><span class="o">.</span><span class="na">getStart</span><span class="o">());</span>
            <span class="o">}</span>
        <span class="o">}</span>
</pre></div>


Here we build a list of every ant to food combination and store the distance.  Then we sort the ArrayList so we get the shortest distances first when looping through the routes.  Next we loop through all possible combinations and if the ant has not been ordered and the food has not been targeted yet, we issue a new order.  We also save a list of target locations to make sure only 1 ant is going for a food item.

</div>

</div>

<h3 id="the-results">The Results</h3>
<p>Let's run the bot again and see how we do.</p>
<div class="codehilite"><pre><span class="nl">C:</span><span class="err">\</span><span class="n">aichallenge</span><span class="o">&gt;</span><span class="n">tutorial</span><span class="p">.</span><span class="n">cmd</span>
<span class="n">running</span> <span class="k">for</span> <span class="mi">60</span> <span class="n">turns</span>
                  <span class="n">ant_count</span> <span class="n">c_turns</span> <span class="n">climb</span><span class="o">?</span> <span class="n">cutoff</span> <span class="n">food</span> <span class="n">r_turn</span> <span class="n">ranking_bots</span> <span class="n">s_alive</span> <span class="n">s_hills</span> <span class="n">score</span>  <span class="n">w_turn</span> <span class="n">winning</span>
<span class="n">turn</span>    <span class="mi">0</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">18</span>    <span class="mi">0</span>        <span class="n">None</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">0</span>     <span class="n">None</span>
<span class="n">turn</span>    <span class="mi">1</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">2</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="p">...</span>
<span class="n">turn</span>   <span class="mi">60</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">4</span><span class="p">,</span><span class="mi">6</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">6</span>     <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
<span class="n">score</span> <span class="mi">1</span> <span class="mi">1</span>
<span class="n">status</span> <span class="n">survived</span> <span class="n">survived</span>
<span class="n">playerturns</span> <span class="mi">60</span> <span class="mi">60</span>
</pre></div>


<p>Here is the replay:</p>
<div class="codehilite"><pre><span class="c"># [ { &quot;embedded&quot;: true, &quot;game&quot;: &quot;2 - Gathering Food&quot; }, 600, 600, { &quot;speedFactor&quot;: 0, &quot;speedFastest&quot;: 2, &quot;speedSlowest&quot;: 2, &quot;zoom&quot;: 1 }, &quot;example_games/tutorial.2.replay&quot; ]</span>
</pre></div>


<p>Hey, we did pretty good!  All the food that we could see was picked up.  If you look closely at the replay, you can see we still have 3 ants in the hive that can't spawn.  Oops, we better take care of that.  If ants can't get out, they can't help us win.</p>
<h3 id="next">Next</h3>
<p>On to <a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a></p>
<!--</MarkdownReplacement>-->

<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>