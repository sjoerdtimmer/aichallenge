<?php

$title = "Ants Tutorial";
require_once('header.php');

?>

<!--<MarkdownReplacement with="competition-Tutorial.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="ants-tutorial">Ants Tutorial</h2>
<div class="toc">

*   <a href="ants_tutorial.php">Setting Up</a>
*   <a href="ants_tutorial_step_1.php">Step 1: Avoiding Collisions</a>
*   <a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a>
*   <a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a>
*   <a href="ants_tutorial_step_4.php">Step 4: Explore the Map</a>
*   <a href="ants_tutorial_step_5.php">Step 5: Attack the Enemy Hills</a>

</div>

<p>The strategies implemented in the starter packages are only meant to serve as a starting point for making your bot.  In fact, it's almost the worst strategy to use.  The starter packages also come with some useful functions to help you develop a smarter strategy.  This page will walk you through a series of improvements.  With each step that you complete, your bot will get smarter and you should notice your ranking start to rise.</p>
<h3 id="prerequisites">Prerequisites</h3>
<p>For this tutorial, we will be using the Python starter package.  In order to use python, you must have a python interpreter downloaded and installed on your machine.  See <a href="getting_started_with_python.php">Getting Started with Python</a>.</p>
<p><strong>Note: The tools come with the game engine written in python.  You will need to install a python interpreter to run the game engine regardless of which language you are programming in.</strong></p>
<p>You'll also want to download the <a href="using_the_tools.php">tools</a> and install them on your machine.</p>
<h3 id="setting-up">Setting Up</h3>
<p>Create a folder to put both the tools and the starter bot and unzip both files in that location.  You should have something that looks like this:</p>
<div class="codehilite"><pre><span class="nl">C:</span><span class="err">\</span><span class="n">aichallenge</span><span class="o">&gt;</span><span class="n">tree</span>
<span class="n">Folder</span> <span class="n">PATH</span> <span class="n">listing</span>
<span class="nl">C:</span><span class="p">.</span>
<span class="o">+----</span><span class="n">tools</span>
    <span class="o">+---</span><span class="n">mapgen</span>
    <span class="o">+---</span><span class="n">maps</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">example</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">maze</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">multi_hill_maze</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">symmetric_random_walk</span>
    <span class="o">+---</span><span class="n">sample_bots</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">csharp</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">java</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">php</span>
    <span class="o">|</span>   <span class="o">|</span>   <span class="o">+---</span><span class="n">tests</span>
    <span class="o">|</span>   <span class="o">+---</span><span class="n">python</span>
    <span class="o">+---</span><span class="n">submission_test</span>
    <span class="o">+---</span><span class="n">visualizer</span>
        <span class="o">+---</span><span class="n">data</span>
        <span class="o">|</span>   <span class="o">+---</span><span class="n">img</span>
        <span class="o">+---</span><span class="n">js</span>
</pre></div>


<div class="tab_sync">    
<div class="tab_content" title="Python">

    C:\aichallenge>dir /b
    ants.py
    MyBot.py
    tools

</div>

<div class="tab_content" title="Java">

    C:\aichallenge>dir /b
    AbstractSystemInputParser.java
    AbstractSystemInputReader.java
    Aim.java
    Ants.java
    Bot.java
    Ilk.java
    make.cmd
    Makefile
    Manifest.txt
    MyBot.java
    Order.java
    Tile.java
    tools

</div>

</div>

<h3 id="testing">Testing</h3>
<p>Now lets make sure everything is working by running a test game.  The tools comes with a utility called "playgame.py" that will help up test our bot.  It also comes with an example script called "play_one_game.cmd" to show you how to use it.</p>
<div class="codehilite"><pre><span class="nl">C:</span><span class="err">\</span><span class="n">aichallenge</span><span class="o">&gt;</span><span class="n">tools</span><span class="err">\</span><span class="n">play_one_game</span><span class="p">.</span><span class="n">cmd</span>
<span class="n">running</span> <span class="k">for</span> <span class="mi">500</span> <span class="n">turns</span>
                     <span class="n">ant_count</span>    <span class="n">c_turns</span>    <span class="n">climb</span><span class="o">?</span>    <span class="n">cutoff</span> <span class="n">food</span> <span class="n">r_turn</span> <span class="n">ranking_bots</span>   <span class="n">s_alive</span>      <span class="n">s_hills</span>       <span class="n">score</span>     <span class="n">w_turn</span> <span class="n">winning</span>
<span class="n">turn</span>    <span class="mi">0</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">20</span>    <span class="mi">0</span>        <span class="n">None</span>     <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">0</span>     <span class="n">None</span>
<span class="n">turn</span>    <span class="mi">1</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">20</span>    <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">2</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">24</span>    <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">3</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">24</span>    <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">4</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">22</span>    <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">]</span>
<span class="n">turn</span>    <span class="mi">5</span> <span class="n">stats</span><span class="o">:</span>  <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>    <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="o">-</span>     <span class="mi">22</span>    <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span> <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">]</span>
<span class="p">...</span>
</pre></div>


<p>If you saw the preceding output, then everything should be working.</p>
<h3 id="create-test-script">Create Test Script</h3>
<p>Now, let's create our own script for this tutorial that uses a new map and our own bot.</p>
<div class="tab_sync">
<div class="tab_content" title="Windows">

Create a file called "tutorial.cmd".

<div class="codehilite"><pre>C:\aichallenge&gt;notepad tutorial.cmd
</pre></div>

</div>

<div class="tab_content" title="Linux">

Create a file called "tutorial.sh".

<div class="codehilite"><pre>user@localhost:~$ gedit tutorial.sh
</pre></div>

After editing the file, make it executable:

<div class="codehilite"><pre>user@localhost:~$ chmod u+x tutorial.sh
</pre></div>

</div>

</div>

<p>The contents of the text file will be:</p>
<div class="tab_sync">
<div class="tab_content" title="Python">

<div class="codehilite"><pre>python tools/playgame.py &quot;python MyBot.py&quot; &quot;python tools/sample_bots/python/HunterBot.py&quot; --map_file tools/maps/example/tutorial1.map --log_dir game_logs --turns 60 --scenario --food none --player_seed 7 --verbose -e
</pre></div>

</div>

<div class="tab_content" title="Java">

<div class="codehilite"><pre>python tools/playgame.py &quot;java -jar MyBot.jar&quot; &quot;python tools/sample_bots/python/HunterBot.py&quot; --map_file tools/maps/example/tutorial1.map --log_dir game_logs --turns 60 --scenario --food none --player_seed 7 --verbose -e
</pre></div>

The java bot needs to be compiled into a jar file for us to use.  You can run the following command to create the file:

<div class="codehilite"><pre><span class="n">make</span>
</pre></div>

</div>

</div>

<ul>
<li>The first 2 options are the names of the bots to run.  We'll be using HunterBot as our opponent.</li>
<li>The <code>--map_file</code> options specifies the map to use.  This is a simple map with 2 ant hills.</li>
<li>The <code>--log_dir</code> options specifies a location to store the replay file, and optionally the logs of bot input and output and errors.</li>
<li>The <code>--turns</code> options specifies when to stop the game if it goes too long.  We don't want a lot of extra output, so will keep it to 60 turns.</li>
<li>The <code>--scenario</code> option allows us to use the food specified on the map as the starting food.  It has been specially placed for this tutorial. (remove this for real games)</li>
<li>The <code>--food none</code> option allows us to turn off the food spawning during the game.  Again, it will be off just for this tutorial. (remove this for real games)</li>
<li>The <code>--player_seed</code> option ensures that you can get the same game results as in the tutorial.  HunterBot will use this value to initialize the random number generator, so it will always do the same thing.  <em>(Note: if you want your bot to be able to replay games for debugging, you'll want to implement this as well.)</em></li>
<li>The <code>--verbose</code> option will print a running total of game stats so we can watch the progress as the game is played.</li>
<li>The <code>-e</code> option will output any bot errors to the console, so if you make a mistake during the tutorial you can see what the error message is.
<em>(Note: remove the <code>--scenario</code> and <code>--food none</code>  options when you want to play games on different maps.)
</em>(Note: the tutorial was made with view radius 55, which is not the official view radius.  You can add <code>--viewradius 55</code> to the engine to override the default and make the tutorial bot behave the same as the same replays.)
(Both HunterBot and the tutorial bot we will be making are deterministic, meaning if you give them the same input, they will produce the same set of moves.  This means if you follow along exactly, you should see the tutorial games play out exactly the same on your machine.  The python code that sorts lists will sort every element, the java code wasn't implemented to sort correctly, so results may differ.)</li>
</ul>
<p>Let's run the command and see how the starter bot does.</p>
<div class="tab_sync">
<div class="tab_content" title="Windows">

<div class="codehilite"><pre>C:\aichallenge&gt;tutorial
</pre></div>

</div>

<div class="tab_content" title="Linux">

<div class="codehilite"><pre>user@localhost:~$ ./tutorial.sh
</pre></div>

</div>

</div>

<p>You should see the following output:</p>
<div class="codehilite"><pre>    <span class="n">running</span> <span class="k">for</span> <span class="mi">60</span> <span class="n">turns</span>
                      <span class="n">ant_count</span> <span class="n">c_turns</span> <span class="n">climb</span><span class="o">?</span> <span class="n">cutoff</span> <span class="n">food</span> <span class="n">r_turn</span> <span class="n">ranking_bots</span> <span class="n">s_alive</span> <span class="n">s_hills</span> <span class="n">score</span>  <span class="n">w_turn</span> <span class="n">winning</span>
    <span class="n">turn</span>    <span class="mi">0</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">18</span>    <span class="mi">0</span>        <span class="n">None</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">0</span>     <span class="n">None</span>
    <span class="n">turn</span>    <span class="mi">1</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">2</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">16</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">3</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">15</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">4</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">14</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">5</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">14</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">6</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">14</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">7</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">2</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">14</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">turn</span>    <span class="mi">8</span> <span class="n">bot</span> <span class="mi">0</span> <span class="n">eliminated</span>
    <span class="n">turn</span>    <span class="mi">8</span> <span class="n">stats</span><span class="o">:</span>   <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>     <span class="mi">0</span>    <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>    <span class="o">-</span>     <span class="mi">14</span>    <span class="mi">1</span>       <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">0</span><span class="p">]</span>      <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>  <span class="p">[</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>   <span class="mi">1</span>     <span class="p">[</span><span class="mi">0</span><span class="p">,</span><span class="mi">1</span><span class="p">]</span>
    <span class="n">score</span> <span class="mi">1</span> <span class="mi">3</span>
    <span class="n">status</span> <span class="n">eliminated</span> <span class="n">survived</span>
    <span class="n">playerturns</span> <span class="mi">8</span> <span class="mi">8</span>
</pre></div>


<p>The game only ran for 8 turns, which is very fast.  It looks like player 0 (that's us) got eliminated.  A browser should have launched to show you the game in the visualizer.</p>
<div class="codehilite"><pre><span class="c"># [ { &quot;embedded&quot;: true }, 600, 600, { &quot;speedFactor&quot;: 0, &quot;speedFastest&quot;: 2, &quot;speedSlowest&quot;: 2, &quot;zoom&quot;: 1 }, &quot;example_games/tutorial.0.replay&quot; ]</span>
</pre></div>


<p>You can see the starter bot's strategy is so horrible, it kills itself by colliding 2 ants.  That will be our first improvement.</p>
<h3 id="next">Next</h3>
<p>Here's the list of improvements we'll be implementing in this tutorial:</p>
<ol>
<li><a href="ants_tutorial_step_1.php">Step 1: Avoiding Collisions</a></li>
<li><a href="ants_tutorial_step_2.php">Step 2: Gathering Food</a></li>
<li><a href="ants_tutorial_step_3.php">Step 3: Not Blocking Hills</a></li>
<li><a href="ants_tutorial_step_4.php">Step 4: Explore the Map</a></li>
<li><a href="ants_tutorial_step_5.php">Step 5: Attack the Enemy Hills</a></li>
</ol>
<!--</MarkdownReplacement>-->

<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>