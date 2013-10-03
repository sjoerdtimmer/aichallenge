<?php

require_once('header.php');

?>

<h1>Ants Game Specification</h1>

<p>Contents:</p>

<ol>
<li><a href="#Turns-and-Phases">Turns and Phases</a></li>
<li><a href="#Scoring">Scoring</a></li>
<li><a href="#Cutoff-Rules">Cutoff Rules</a></li>
<li><a href="#Food-Harvesting">Food Harvesting</a></li>
<li><a href="#Ant-Spawning">Ant Spawning</a></li>
<li><a href="#Food-Spawning">Food Spawning</a></li>
<li><a href="#Battle-Resolution">Battle Resolution</a></li>
<ol>
<li><a href="specification_battle.php">Focus Battle Resolution</a></li>
</ol>
<li><a href="#Hill-Razing">Battle Resolution</a></li>
<li><a href="#Bot-Input">Bot Input</a></li>
<li><a href="#Bot-Output">Bot Output</a></li>
<li><a href="#Map-Format">Map Format</a></li>
<li><a href="#Replay-Format">Replay Format</a></li>
</ol>

<div id="Turns-and-Phases">
<!--<MarkdownReplacement with="competition-Turns-and-Phases.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="turns-and-phases">Turns and Phases</h2>
<h3 id="setup">Setup</h3>
<p>Each bot is sent some starting information for the game, including map size, max turns and turn timings.  After the bot has processed this data, it should return a 'go'.  After all bots are ready, the game will start.</p>
<h3 id="turns">Turns</h3>
<p>Once each of the bots has indicated it has finished setting up, the game engine performs the following steps repeatedly:</p>
<ol>
<li>Send the game state to the players</li>
<li>Receive orders from the players</li>
<li>Perform the phases and update the game state</li>
<li>Check for endgame conditions</li>
</ol>
<p>There is a specified maximum turn limit for each map.  This will be adjusted continuously during the contest.</p>
<p>A <em>turn</em> is defined as the above steps. They are performed up to the maximum number of turns times and then the game stops. </p>
<h3 id="phases">Phases</h3>
<p>After receiving complete orders from the players, the engine then updates the game state, advancing the game to the next turn. This happens in 6 phases:</p>
<ul>
<li>move (execute orders)</li>
<li>attack</li>
<li>raze hills</li>
<li>spawn ants</li>
<li>gather food</li>
<li>spawn food</li>
</ul>
<h3 id="endbot-conditions">Endbot Conditions</h3>
<p>Any of the following conditions will cause a player to finish participating in a game:</p>
<ul>
<li>The player has no live ants left remaining on the map.</li>
<li>The bot crashed.</li>
<li>The bot exceeded the time limit without completing its orders.</li>
<li>A bot attempts to do something that the tournament manager deems a security issue and is disqualified.</li>
</ul>
<p>If a bot stops participating due to a crash or timeout, their ants remain on the board and can still collide and battle with other ants. Their ants just do not make any future moves and opponents are not explicitly told those ants' owners are no longer participating.</p>
<p>If a bot crashes or times out on a given turn then none of the moves received from that bot will be executed for that turn. </p>
<h3 id="ranking">Ranking</h3>
<p>At the end of the game, each player is ranked based off the final scores, with a tie resulting in each player having the same ranking. The difference in scores is not reflected in the general ranking of bots, only the relative positions for each game.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Scoring">
<!--<MarkdownReplacement with="competition-Scoring.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="scoring">Scoring</h2>
<p>The objective of the game is to get the highest score.  Points are awarded by attacking and defending hills.</p>
<ul>
<li>Each bot starts with 1 point per hill</li>
<li>Razing an enemy hill is 2 points</li>
<li>Losing a hill is -1 points</li>
</ul>
<p>This means if you don't attack and lose all your hills, you will end up with 0 points.</p>
<p>If the game ends with only 1 bot remaining, any enemy hills not razed will be awarded to the remaining bot.  This is done so that if another bot crashes or times out the remaining bot isn't denied the points for attacking.  These are called bonus points.</p>
<ul>
<li>2 bonus points per remaining hill to the remaining bot</li>
<li>-1 point per remaining hill to the owner</li>
</ul>
<!--</MarkdownReplacement>-->
</div>

<div id="Cutoff-Rules">
<!--<MarkdownReplacement with="competition-Cutoff-Rules.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="cutoff-rules">Cutoff Rules</h2>
<p>To ensure meaningful games are being played on the server, there are several rules in place to cut games short for various reasons.</p>
<ul>
<li>Food Not Being Gathered</li>
</ul>
<p>If a game consists of bots that aren't capable of gathering food, then the game is cutoff.  It is assumed that these are starter bots or very unsophisticated bots.  If the total amount of food is 90% of the count of food and ants for 150 turns then the cutoff is invoked.</p>
<ul>
<li>Ants Not Razing Hills</li>
</ul>
<p>If a game consists of a dominant bot that isn't razing enemy hills, then the game is cutoff.  It is assumed that the bot would probably not lose the lead and just isn't sophisticated enough to go in for the kill.  If the total amount of live ants for the dominant bot is 90% of the count of food and ants for 150 turns then the cutoff is invoked.  <strong>Update</strong>: Because a bot with a large hive count cannot be taken if they do not move off of the hill, we will stall this cutoff if there is any dead ant on top of a hill not owned by the dominant bot.  This gives the dominant bot time to drain the hive count to 0 and score for razing the hill.</p>
<ul>
<li>Lone Survivor</li>
</ul>
<p>If there is only 1 bot left alive in the game, then the game is cutoff.  All other bots have been completely eliminated (no ants on the map) or have crashed or timed out.  Remaining enemy hills are awarded to the last bot and points subtracted from the hill owners.</p>
<ul>
<li>Rank Stabilized</li>
</ul>
<p>If there is no bot with hills left that can gain enough points to gain in rank, then the game is cutoff.  Even though bots without hills left could still possibly gain in rank, the game is not extended them, only those with hills.  For each bot with a hill, it's maximum score (calculated assuming it could capture all remaining enemy hills) is compared to each opponents minimum score (calculated assuming it would lose all remaining hills).  If any score difference can overtake or break a tie then the game continues.  If no bot meets this criteria, the game is stopped.</p>
<p>(e.g. For a 4 player game, if bot A razes the hills of bot B and C, the scores are A=5, B=0, C=0 and D=1.  Even if bot D razes the hill of bot A the score would be A=4 and D=3, so D can't possible do better than 2nd place and the game ends.)</p>
<p>(e.g. For the same 4 player game, if bot A razes the hill of bot B and bot B still has ants, it is free to attempt to gain points.  But after bot A razes the hill of bot C it is no longer given the opportunity even though it could end with a score of A=4, B=2, C=0, D=0.)</p>
<ul>
<li>Turn Limit Reached</li>
</ul>
<p>There is a maximum turn limit for each map.  Each bot is given the limit.  The game ends at this point.  The limit will be adjusted so about 90%-95% of the games will be ended by this cutoff.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Food-Harvesting">
<!--<MarkdownReplacement with="competition-Food-Harvesting.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="food-harvesting">Food Harvesting</h2>
<p>Harvesting of food occurs each turn after the battle resolution process. If there are ants located within the spawn radius of a food location one of two things will occur:</p>
<ul>
<li>If there exist ants within the spawn radius belonging to more than one distinct bot then the food is destroyed and disappears from the game. </li>
<li>If the ants within the spawn radius all belong to the same bot then the food is harvested and placed into the hive for that bot.</li>
</ul>
<!--</MarkdownReplacement>-->
</div>

<div id="Ant-Spawning">
<!--<MarkdownReplacement with="competition-Ant-Spawning.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="ant-spawning">Ant Spawning</h2>
<p>As food is harvested, it is placed in the "hive".  Each food will spawn 1 ant.  Ants are only spawned at hills.</p>
<ul>
<li>The hill must not have been razed.</li>
<li>The hill must not be occupied by an ant.</li>
</ul>
<p>Only 1 ant can be spawned on a hill each turn.</p>
<p>For maps with multiple hills, 1 ant can be spawned at each hill if there is enough food in the hive.  If there is less food in the hive than there are hills, each hill is given a priority.  The last hill to have an ant on top is chosen last or the hill to have been touched the longest ago is chosen first.  In case of a tie, a hill is chosen at random.</p>
<p>This means that if you always move ants off of the hill right away, the spawned ants should be evenly spread between the hills.</p>
<p>You can control which hill to spawn at by keeping an ant nearby to block the hill when you don't want it to spawn ants.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Food-Spawning">
<!--<MarkdownReplacement with="competition-Food-Spawning.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="food-spawning">Food spawning</h2>
<p>Food spawning is done symmetrically.  Every map is symmetric, meaning each bot's starting position looks like every other bots starting position.</p>
<ul>
<li>Each game will start with a few food items placed within the bots starting vision, about 2-5.</li>
<li>Starting food will be placed randomly on the map as well, symmetrically.</li>
<li>Each game has a hidden food rate that will increase the amount of food in the game.  Then the amount to be spawned is divisible by the number of players, then that amount of food will spawn symmetrically.</li>
<li>The entire map is divided into sets of squares that are symmetric.  The sets are shuffled into a random order. When food is spawned, the next set is chosen.  When all the sets have been chosen, they are shuffled again.</li>
<li>Every set will spawn at least once before a set spawns a second time.  This means if you see food spawn, it may be awhile before it spawns again, unless it was the last set of the random order and was then shuffled to be the first set of the next random order.</li>
<li>Sometimes squares are equidistant to 2 bots.  This makes for a set that is smaller than normal.  The food rate takes this into account when spawning food.</li>
<li>Some maps have mirror symmetry so that a set of symmetric squares are touching.  It would be unfair to spawn so much food in one place, so these sets are not used.  If you can find a mirror symmetry after exploring the map, then you can avoid those spots when looking for food.</li>
</ul>
<p>The best way to gather the most food is to explore the map and cover the most area.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Battle-Resolution">
<!--<MarkdownReplacement with="competition-Battle-Resolution.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="battle-resolution">Battle Resolution</h2>
<div class="codehilite"><pre>    <span class="c1">// how to check if an ant dies</span>
    <span class="k">for</span> <span class="n">every</span> <span class="n">ant</span><span class="o">:</span>
        <span class="k">for</span> <span class="n">each</span> <span class="n">enemy</span> <span class="n">in</span> <span class="n">range</span> <span class="n">of</span> <span class="n">ant</span> <span class="p">(</span><span class="n">using</span> <span class="n">attackadius2</span><span class="p">)</span><span class="o">:</span>
            <span class="k">if</span> <span class="p">(</span><span class="n">enemies</span><span class="p">(</span><span class="n">of</span> <span class="n">ant</span><span class="p">)</span> <span class="n">in</span> <span class="n">range</span> <span class="n">of</span> <span class="n">ant</span><span class="p">)</span> <span class="o">&gt;=</span> <span class="p">(</span><span class="n">enemies</span><span class="p">(</span><span class="n">of</span> <span class="n">enemy</span><span class="p">)</span> <span class="n">in</span> <span class="n">range</span> <span class="n">of</span> <span class="n">enemy</span><span class="p">)</span> <span class="n">then</span>
                <span class="n">the</span> <span class="n">ant</span> <span class="n">is</span> <span class="n">marked</span> <span class="n">dead</span> <span class="p">(</span><span class="n">actual</span> <span class="n">removal</span> <span class="n">is</span> <span class="n">done</span> <span class="n">after</span> <span class="n">all</span> <span class="n">battles</span> <span class="n">are</span> <span class="n">resolved</span><span class="p">)</span>
                <span class="k">break</span> <span class="n">out</span> <span class="n">of</span> <span class="n">enemy</span> <span class="n">loop</span>
</pre></div>


<ul>
<li>Ants within the attack radius of each other kill each other (sometimes).</li>
<li>If you have more ants than another bot in the area, you won't die (usually).</li>
<li>The battle resolution is locally deterministic, meaning<ul>
<li>you only need to know an ants surroundings</li>
<li>and it is easy for the computer to solve.</li>
</ul>
</li>
<li>The battle resolution is:<ul>
<li>fun!</li>
<li>means killing your foe without taking loses</li>
<li>enables defending your hill with a few ants and inflicting massive losses to the enemy (Sparta!)</li>
<li>allows for awesome formations yet to be discovered</li>
<li>gets really weird with 3 or more bots in the fight (wait for the other guys to kill each other?)</li>
<li>doesn't need to concern you if you use Ender's method of winning the game (the ant hill is down)</li>
<li>is the most fun part of this game!</li>
</ul>
</li>
</ul>
<p>You should read more about it on the <a href="specification_battle.php">Focus Battle Resolution Page</a></p>
<!--</MarkdownReplacement>-->
</div>

<div id="Hill-Razing">
<!--<MarkdownReplacement with="competition-Hill-Razing.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="hill-razing">Hill Razing</h2>
<p>The objective of the game is to raze your opponents hills and defend your own hill.</p>
<p>A hill is razed (destroyed) when:</p>
<ul>
<li>An enemy ant is at the same location as the hill after the attack phase.</li>
</ul>
<p>Razed hills do not spawn ants anymore.  If all your hills have been razed, but you still have ants, your bot is still alive and your ants can still move, attack, gather food and raze hills.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Bot-Input">
<!--<MarkdownReplacement with="competition-Bot-Input.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="bot-input">Bot Input</h2>
<p><strong>Parameter Information:</strong><br />
At the start of a game, each bot is passed general parameters about the game, this begins with "turn 0" on its own line. Parameters will then be passed on separate lines with the following format:</p>
<div class="codehilite"><pre><span class="n">type</span> <span class="n">value</span>
</pre></div>


<p>The type of a value is determined by the parameter type. Currently all values, except for <code>player_seed</code>, are 32bit signed integers. <code>player_seed</code> is a 64bit signed integer. If the bot encounters an unknown parameter it should treat the value as an opaque string and not try to parse it in any way.</p>
<p>The set of parameter types may be added to the spec in the future, but none will be removed once they have been added. Here is the current list of parameters that will be passed to the bots:</p>
<div class="codehilite"><pre><span class="s">&quot;loadtime&quot;</span>       <span class="err">#</span> <span class="n">in</span> <span class="n">milliseconds</span><span class="p">,</span> <span class="n">time</span> <span class="n">given</span> <span class="k">for</span> <span class="n">bot</span> <span class="n">to</span> <span class="n">start</span> <span class="n">up</span> <span class="n">after</span> <span class="n">it</span> <span class="n">is</span> <span class="n">given</span> <span class="s">&quot;ready&quot;</span> <span class="p">(</span><span class="n">see</span> <span class="n">below</span><span class="p">)</span>
<span class="s">&quot;turntime&quot;</span>       <span class="err">#</span> <span class="n">in</span> <span class="n">milliseconds</span><span class="p">,</span> <span class="n">time</span> <span class="n">given</span> <span class="n">to</span> <span class="n">the</span> <span class="n">bot</span> <span class="n">each</span> <span class="n">turn</span>
<span class="s">&quot;rows&quot;</span>           <span class="err">#</span> <span class="n">number</span> <span class="n">of</span> <span class="n">rows</span> <span class="n">in</span> <span class="n">the</span> <span class="n">map</span>
<span class="s">&quot;cols&quot;</span>           <span class="err">#</span> <span class="n">number</span> <span class="n">of</span> <span class="n">columns</span> <span class="n">in</span> <span class="n">the</span> <span class="n">map</span>
<span class="s">&quot;turns&quot;</span>          <span class="err">#</span> <span class="n">maximum</span> <span class="n">number</span> <span class="n">of</span> <span class="n">turns</span> <span class="n">in</span> <span class="n">the</span> <span class="n">game</span>
<span class="s">&quot;viewradius2&quot;</span>    <span class="err">#</span> <span class="n">view</span> <span class="n">radius</span> <span class="n">squared</span>
<span class="s">&quot;attackradius2&quot;</span>  <span class="err">#</span> <span class="n">battle</span> <span class="n">radius</span> <span class="n">squared</span>
<span class="s">&quot;spawnradius2&quot;</span>   <span class="err">#</span> <span class="n">food</span> <span class="n">gathering</span> <span class="n">radius</span> <span class="n">squared</span> <span class="p">(</span><span class="n">name</span> <span class="n">is</span> <span class="n">an</span> <span class="n">unfortunate</span> <span class="n">historical</span> <span class="n">artifact</span><span class="p">)</span>
<span class="s">&quot;player_seed&quot;</span>    <span class="err">#</span> <span class="n">seed</span> <span class="k">for</span> <span class="n">random</span> <span class="n">number</span> <span class="n">generator</span><span class="p">,</span> <span class="n">useful</span> <span class="k">for</span> <span class="n">reproducing</span> <span class="n">games</span>
</pre></div>


<p>All numbers are a string representation of the number.</p>
<p>Once all parameters have been passed you will receive "ready" on a separate line, at which point you are free to set up for as long as the loadtime specifies.</p>
<p><strong>Turn Information:</strong><br />
Each following turn begins with one of the following lines:</p>
<div class="codehilite"><pre><span class="n">turn</span> <span class="n">turnNo</span>
<span class="n">end</span>
</pre></div>


<p>"end" indicates that the game is over, the winner of the game will receive information for the final state of the game following this, should they wish to use it for local testing.</p>
<p>If the game is over, bots will then receive two lines giving the number of players and scores in the following format:</p>
<div class="codehilite"><pre><span class="n">players</span> <span class="n">noPlayers</span>
<span class="n">score</span> <span class="n">p1Score</span> <span class="p">...</span> <span class="n">pnScore</span>
</pre></div>


<p>You are then passed information about the squares you can currently see with the following format:</p>
<div class="codehilite"><pre><span class="n">w</span> <span class="n">row</span> <span class="n">col</span>                            <span class="err">#</span> <span class="n">water</span>
<span class="n">f</span> <span class="n">row</span> <span class="n">col</span>                            <span class="err">#</span> <span class="n">food</span>
<span class="n">h</span> <span class="n">row</span> <span class="n">col</span> <span class="n">owner</span>                      <span class="err">#</span> <span class="n">ant</span> <span class="n">hill</span>
<span class="n">a</span> <span class="n">row</span> <span class="n">col</span> <span class="n">owner</span>                      <span class="err">#</span> <span class="n">live</span> <span class="n">ant</span>
<span class="n">d</span> <span class="n">row</span> <span class="n">col</span> <span class="n">owner</span>                      <span class="err">#</span> <span class="n">dead</span> <span class="n">ant</span>
</pre></div>


<p>The end of input for a turn is indicated by receiving "go" on its own line.</p>
<p>You are always passed information as though you are player zero, the first enemy you see always appears as player one, and so on. This helps to ensure that you do not know how many players started in the game.</p>
<p>Information about a water square will only be sent the first turn in which it is visible by one of your live ants (to reduce the amount of data transferred).</p>
<p>You will be passed information for live ant, food and hill squares every turn they are within sight of one of your live ants. Food and hills do not move.  They will be sent every turn.  If a food is gathered or a hill is razed out of your view radius, you will not be notified.  When your ant move back into range it will not receive any info about missing food or razed hills.</p>
<p>Information is given for ants that died during the collision or battle resolution of the previous turn if it is in a square currently visible by one of your live ants.  Information is always given about your own dead ants even if you can't see the square.  These are merely for your information if you wish to use them, they can otherwise be thought of as land and moved into that turn.</p>
<p><strong>Sample Input:</strong>  </p>
<div class="codehilite"><pre><span class="cp"># [ { &quot;embedded&quot;: true, &quot;decorated&quot;: false }, 200, 200, {} ]</span>
<span class="n">rows</span> <span class="mi">20</span> 
<span class="n">cols</span> <span class="mi">20</span> 
<span class="n">players</span> <span class="mi">2</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">.....</span><span class="o">*</span><span class="p">..............</span>
<span class="n">m</span> <span class="p">......</span><span class="o">%</span><span class="p">..</span><span class="n">b</span><span class="p">.</span><span class="mf">.1</span><span class="p">.......</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">........</span><span class="n">aa</span><span class="p">..........</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
</pre></div>


<p>Below is sample input for player 'a' in the above game:</p>
<div class="codehilite"><pre><span class="n">turn</span> <span class="mi">0</span>
<span class="n">loadtime</span> <span class="mi">3000</span>  
<span class="n">turntime</span> <span class="mi">1000</span>  
<span class="n">rows</span> <span class="mi">20</span>  
<span class="n">cols</span> <span class="mi">20</span>  
<span class="n">turns</span> <span class="mi">500</span>  
<span class="n">viewradius2</span> <span class="mi">55</span>  
<span class="n">attackradius2</span> <span class="mi">5</span>  
<span class="n">spawnradius2</span> <span class="mi">1</span>  
<span class="n">player_seed</span> <span class="mi">42</span>
<span class="n">ready</span>

<span class="n">turn</span> <span class="mi">1</span>
<span class="n">f</span> <span class="mi">6</span> <span class="mi">5</span>
<span class="n">w</span> <span class="mi">7</span> <span class="mi">6</span>
<span class="n">a</span> <span class="mi">7</span> <span class="mi">9</span> <span class="mi">1</span> 
<span class="n">a</span> <span class="mi">10</span> <span class="mi">8</span> <span class="mi">0</span>
<span class="n">a</span> <span class="mi">10</span> <span class="mi">9</span> <span class="mi">0</span>
<span class="n">h</span> <span class="mi">7</span> <span class="mi">12</span> <span class="mi">1</span>
<span class="n">go</span>

<span class="n">end</span>
<span class="n">players</span> <span class="mi">2</span>
<span class="n">score</span> <span class="mi">1</span> <span class="mi">0</span>
<span class="n">f</span> <span class="mi">6</span> <span class="mi">5</span>
<span class="n">d</span> <span class="mi">7</span> <span class="mi">8</span> <span class="mi">1</span> 
<span class="n">a</span> <span class="mi">9</span> <span class="mi">8</span> <span class="mi">0</span>
<span class="n">a</span> <span class="mi">9</span> <span class="mi">9</span> <span class="mi">0</span>
<span class="n">go</span>
</pre></div>


<p>Below is sample input for player 'b' in the above game, starting from the first turn:</p>
<div class="codehilite"><pre><span class="n">turn</span> <span class="mi">1</span>
<span class="n">f</span> <span class="mi">6</span> <span class="mi">5</span>
<span class="n">w</span> <span class="mi">7</span> <span class="mi">6</span>
<span class="n">a</span> <span class="mi">7</span> <span class="mi">9</span> <span class="mi">0</span> 
<span class="n">a</span> <span class="mi">10</span> <span class="mi">8</span> <span class="mi">1</span>
<span class="n">a</span> <span class="mi">10</span> <span class="mi">9</span> <span class="mi">1</span>
<span class="n">go</span>

<span class="n">end</span>
<span class="n">players</span> <span class="mi">2</span>
<span class="n">score</span> <span class="mi">1</span> <span class="mi">0</span>
<span class="n">go</span>
</pre></div>


<h3 id="fog-of-war">Fog of War</h3>
<p>Each bot is passed a parameter at the start of the game indicating the square of each ants visibility, which is how far each ant can see around them. At the moment this is set at 55, giving a view radius of approximately 7.4. </p>
<p>Each turn you are only given current information for the squares that your live ants can currently see.</p>
<h3 id="distance">Distance</h3>
<p>Distances are used for the view radius, attack radius and spawn radius.  You are given the radii squared in order to avoid floating point numbers and keep to integers.</p>
<p>Distances are calculated using the Euclidean metric, which gives the straight line distance between two points. For two locations <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='> and <img class='latex-inline math-true' alt='b' id='b' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAcAAAALBAMAAABBvoqbAAAALVBMVEX///8AAAC6urpEREQiIiKIiIhUVFQyMjKqqqru7u4QEBCYmJhmZmbc3Nx2dnZCGt3EAAAAOElEQVQI12NgMmEAgjAQUQEiJIGYaXXPBQaeAu4EBpYGVgcGxglsCQy3GBgVGKYwvGFg4I4OYAAAye0I/3BzoiEAAAAASUVORK5CYII='>, this can be calculated as follows:</p>
<p><img class='latex-inline math-true' alt='drminabsarowbrowtextrowsabsarowbrow' id='drminabsarowbro' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAacAAAASBAMAAAANsQzZAAAAMFBMVEX///8AAACIiIgQEBDMzMzc3NyYmJhUVFTu7u52dnZEREQiIiIyMjJmZma6urqqqqoGVDknAAADr0lEQVRIx51WTWgTQRT+ut3dbtJmk4OFeJGg0CKiVvxB8ZKq9CLCloJeCoYWLKloF0GqHmSDVLAFyaEVFLS1oKIi7EUQwVoPgjeDqGARE1FBRTGIFvGvvslmm1mTTSd5Gdg8vm/mm/fm7dsBRGxXVVfIwjXPqFNFVEjLVHPFTI7VOKFeFVGhJquaK2gTNfLrVnGEVvnFFigma4/z+Gh7XH9ziR7bJ7gtYRX9ZmUVR6gx7neYxWQ9dR6S6XH9zSV6LCgYlLAK2iurOEI9S80u5q4l73H9zSV6jz0nFpSwCh5XVnGEBpaYrMw6zwbL4/pbQ6XXIVys5fDdDybakqc3zm/PPUis70tXnry0CqKVVZjQmQNXkfziCSz596t5IIvwIaiTX9ohTzHWK7wZegZpIeG6gHpp3sRkEvMZZbaZOwhGhLNoCdXdmvz1djaUV+zf+IkuPeZpcv4qgfk0bacXM9w7teWPUYaSCg3ljtYqd23tpfycuEFGGnLXZtzDJ+zvxq5+ozEeLrCieEln+kqxXZe+JP0jj8aM0MT3iGSGEiU5RkRhUQ6V3dR3FsZ0yuhHIvwQXDL8VbTbeEHbacfzEjs0q5plKKnQaEnLHUqTpwaY20oUg8ZxpCxan1g27mMNbvxYdMFQ3ELgimQGE2HqNgPDZJQ9RiyswqOLQe0EDgJzwdhgPobL29hZsHlHUUUlmMEF2s5VfCuxm/OSXYY6QXUbUub//tcY122iYBMbvyGbDiuKYezbEHddMFRbRks25xoM1VPsw05T5dHF8tsNFvOc+mjhWB7yGq5T+qogFaf8zFCqE/zL12SWoU75vUbLD6u7wHLLD930hdKihm4bSgTTCr2zjDXSgRWqEUoXXapXQvUILZmi7HNBKUQsrOJB3UbBgqLyu64t17ZAMvgu56tCf7VlWkTu4IPqQUOuDHUaRQ8692LQ20IG6WjVqVgonVCnEP1Ouoyl2YHMmBGIF136OhKqdeAIUlRP77gLDhHlDFuURwN52SnyN3RbSCh3cI1+Uhomv00fFZxCU0yLqLZulNhjuFiOBvJsSG071iPrDSqLZihZSz+HUB7ZHJ45rL4+qAuH4br9FqH43JeDdO5PG/8VYsSthUU5NGipKwpN68FJ4PxaC0NYgLoyyW3TX0V/vg5YPfTkMKeitsXK0aDFhoh9qOoK2ihwthZ+3SqjYsxgfRcer40D72vh160yLnihT1dzxUxJQ8vVMqFeFUV03tuqrtg9teYZdarQ+AfgIHqCMKV+/wAAAABJRU5ErkJggg=='>
<img class='latex-inline math-true' alt='dcminabsacolbcoltextcolsabsacolbcol' id='dcminabsacolbco' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAYEAAAASBAMAAABP8n9OAAAAMFBMVEX///8AAACIiIgQEBDMzMzc3NyYmJhUVFTu7u52dnZEREQiIiIyMjJmZma6urqqqqoGVDknAAAEBklEQVRIx5VWXWgcVRT+djozO7vJzu5Dlu6TLBGSQqiJUEXfpkW0NRWmBFpKK2zTH0mlzaBo7dssWmgN1kFqUSFm9UFsJbgPCnlzX3zwKVNphUhbp1ha0iIuhca8pPHcmZ3NvdNkMvNxYOab754759x77pkBEkA24mg82kiFV1KMLfqWCJ/E0njsij5Q4vLX3BRTy1VmifBBLI1Hzow82FuKGZ2108z9uW+EZzdKZLqTa7Aw+g8C5RbuGhflg6aoKFZ05dbLIPR646ntL6/dhwF0lQNgRtiy0b7uDi6Fjj4o0jWMcfeSFVEa0SpaL4PQ6/pTNTfCkcGIkgezSADroadznRfp+jP3tiPK10kyCL2aG6XGB9BVch4zwlubZHC4c62IdA3fcfcZO6KsdF/52RcYmLApg+FxJzJDx0tpRafO1zhSiShFlxkunKDXXL7HaerMv4PqqTaWXEw8eWThdSroy6c8/cUVE8NLIWUjyS235ICrVfw9eUNU7ofKLdwqtJWmUtKrQsu5cOK27yWt1uRGQFkHOF3DzAQy3BHzA+AV3WKmzGllTBl3qA9cJcyynnzU7HH1lvK4JL/6AnbgP+CQXbcLLdUqeIdCSgPJTfsZCzK/9zf9LeWUvWH1lDG5E/hWKRV/hcdV1ZxW8b1uK82iFVBao5/00pRZcEa5qVkAgiK3mPU68giuQOFGmmcpQclSyLIOzUch/4ZR9LSlZv7H8yElkFvexVc5WlH1DOE9evYLhkQlzKBI9f828CdV0Tcvs9plHrSmFEDT97q6bFMGPqUacWDOIteY5kayAAQlyGCfKblaWSy+HXjIyox61Baj2MJrbP22U61mLeXdvpDSapBb3cCscHgrOCMq97kMhoIM5CHufLIAfK8DzxuyFVDyNqH1Ie/OC4clawlKUEV30Lt80W8P3Sqid0yjfg4PFexDvmoehl7C/PkxZLzjWECHgl3IXeurfyT0uGdEZWWtihSqoitKSTL5nsMCOEdeKlUGnWRGbfaUTVLfXTH41pzxBCU4yWPYuZ8qReJPcoN29aSEBRUnkfnU7KG45HJtCtNU25fQobLL3D5Etjqqct+wZs4VlRnsCU+ymq0pc5SBA4uPiwIgrykzZ1BmjNKHjc7ZthG8Ize5XkQBiEquzUwa2DWMxVW+EAptmunNZSzW8BcKT1Aw0X+sv60OVDG55HXpS8xN/+M5ZI9xzuPjEJXvcSQ4t9LvVXy53X6wdX//BNdh/ACY1+pp4EZAj9r6zCr+GffwPrcHLABBydvMEkB34ugm0BrQvMSjF1P9yH7sWxI0Yukmf3YG9OS/a/lUGVzyLQn2xNJ4HAQupvhhTrO/isMsEXQjjsajmmpVcTfFWMm3/wGSEVJGbANBFwAAAABJRU5ErkJggg=='>
<img class='latex-inline math-true' alt='distanceabsqrtdr2dc2' id='distanceabsqrtd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM0AAAAWBAMAAACRawW2AAAAMFBMVEX///8AAACIiIgQEBDMzMzc3NyYmJhUVFTu7u52dnZEREQiIiIyMjJmZma6urqqqqoGVDknAAACrElEQVRIx71VPWgTYRh+8nOXa2zOFBroJCGBdNJGsQ5OMRb8h5OCoFUIVK0IatBFt8sgtoglg3SzKQoiirSDgm5ZBDM1i0MnLzqoOHgOPR1a4vt995P7E6WEvnDJ+z33vf/P9x3QB/k59C9J9yOMUMe2SErdnjgXHO3TaFjIcNQv8ZJroYft+GArUjZaDb4ORwPywL0oh2yQ2s6gVnY2Q8YXigbktnsxoAQ3uDA1EUYJG81n8ejvbXOylV9QbiEduMd+ovu4/sWEstYrLxor4aipvQshU288o/QsBXc85L/73Uk52XjQSaeTxaCXHT11lZ7F4HiqPdOyVUnV49BGZ2wTMaQr53vqCD2bAS5GS46ptHeo7YnjRucuP4VBypgh528q0sI1TWz8oBYNXK9IBQPHaSNhbD4HNhW71ZZskJ8kc7VwtSIuGk6apsJQ5sYs7Y2UEdbTSGlTiCmYUmvqxLQSK0mv5PQ5IvUvcIxNqknpnSQl8ZzkJTOepxL3gO9QJqbvvvfGYShzwxeD9XhRoIOUXJ7FYaCFE1DuoKYm61Bahs7iMIxNSo+u8DgueWsyo0WmZGUKpXGQp8FQ5oajp5VomzFOuDVMhBIy2M1YsoGaAokN5IiFARE1UfX1DXmIdEXYVvDUw9Gafbg6GPytfhdwCWtYFqjI1VkhjcdChzamIREPOMZpGdE4D3p9Q0Rn47GtvHE42rGhSRw6gzVRek3nYFwUivFMRVzCyDqNJDuOOeI1x+J13GenueE7Xc0up69p5auHocyNeWQL5TF8q+CGoSF3BbmLOT2l46MmN7o41dWRUjgm7oJYIItnvrtrmHu3rLxxOMrc/JfI1o01b1LVfx88qYQZtbfwdbE8fzXvzJLv7ee+fcWOmYVo/O9s4NLvWxzZXUJ2q17+ADlEu7omvc0mAAAAAElFTkSuQmCC'></p>
<p>Two locations <img class='latex-inline math-true' alt='a' id='a' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAHBAMAAAAotXpTAAAAKlBMVEX///8AAADu7u52dnaYmJhmZma6urrc3NwiIiIyMjLMzMyIiIiqqqoQEBB7UAqnAAAAMklEQVQI12NgUHYJY2Bgb2ISYmDgNOBqYGAoLOAOYGBIZOA8pMBQy7CwhIGBe8vqPQwAiRoIXl8ObGgAAAAASUVORK5CYII='> and <img class='latex-inline math-true' alt='b' id='b' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAcAAAALBAMAAABBvoqbAAAALVBMVEX///8AAAC6urpEREQiIiKIiIhUVFQyMjKqqqru7u4QEBCYmJhmZmbc3Nx2dnZCGt3EAAAAOElEQVQI12NgMmEAgjAQUQEiJIGYaXXPBQaeAu4EBpYGVgcGxglsCQy3GBgVGKYwvGFg4I4OYAAAye0I/3BzoiEAAAAASUVORK5CYII='> are considered to be within a distance of <img class='latex-inline math-true' alt='r' id='r' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAHBAMAAADHdxFtAAAAKlBMVEX///8AAACYmJh2dna6urqqqqpUVFTMzMwQEBDu7u7c3NwyMjJERERmZmaSflhxAAAAJUlEQVQI12NgMglKYCjXmLWTYUEyAxC0AzGXAJBgOgAkeAMYGAB70wWe68BfKgAAAABJRU5ErkJggg=='> if <img class='latex-inline math-true' alt='distanceableqr' id='distanceableqr' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAAASBAMAAABhvsuwAAAAMFBMVEX///8AAACIiIgQEBDMzMzc3NyYmJhUVFTu7u52dnZEREQiIiIyMjJmZma6urqqqqoGVDknAAACA0lEQVQ4y51UPWgUQRT+snczTmJus0UOUslxgRMEyRWJ9SUGjH8wIgTyUxzEqAjqok1swp4g5AjKFZJCMHcIimBxFgqWFhFMlTQSUoibSiWF29xqYYhvZva4DaeYZODtfu/tm2/m/S3wv5UsxJQAB18P48rIIQjm4kqn/JtLfwZP/x3BehPZrwDmNrVGzCdRwJhBH9oJUq0UHCepRfhGMeZzuQlYvp3gaAuukSwbeGJPNq81AXfbCaZbsI9kRwHxxI95lK++QEiBDoR2/x0plm76vPqDbtt5qyhyIc6RP9lUDk7tSHxV+zeisPhy6IK9E2nWcJDyp5CQmPJK3uiMTBTEG9uZpBr+hLapbLynG14gYK1EZ4/OPPiI7koyzywXXfUFnAZWcR7yHkpeVwVyNQwUgbKpbATWa02A8rwhIEfgkrTWVR3Y3V5KM0vjJDCI3yhJCBX0mcgGdHhHXB0CcL9uGAZJttD9y9tmmMUm6sx2sLbAHDxjW1QUB4KSqG26WB2+SSJFrxnIQZmHx7HJxVs8xhBn+WS6yGvoa1DYmSGUqYzalqxgUbVbNQq/57miUV1h5UYG8L2I26GP7HVkr2SDVIAvvl3dxcXdACmpbfwYeC4DvNzTZPuZLbti3o/0U9QOPkzRlm9mmFQH8M+0Pu2b4Kw52vTexCHG2Y7/UDJtn/8Asqx/AdEzVRkAAAAASUVORK5CYII='>.</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Bot-Output">
<!--<MarkdownReplacement with="competition-Bot-Output.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="bot-output">Bot Output</h2>
<p>Once the bot has been passed the parameters at the start of the game and it has finished setting up, it is to indicate this to the engine by outputting "go" on its own line.</p>
<p>Each bot may move any number of their ants each turn either north, east, south or west, provided the destination square is not water. Each move should be on a separate line with the following format:</p>
<div class="codehilite"><pre><span class="n">o</span> <span class="n">row</span> <span class="n">col</span> <span class="n">direction</span>
</pre></div>


<p>Grid indexes start from zero and directions are to be passed as either 'N', 'E', 'S' or 'W'. At the end of each turn, bots are to output "go" to indicate to the engine that it has finished issuing moves. </p>
<p><strong>Sample Output:</strong>  </p>
<div class="codehilite"><pre><span class="cp"># [ { &quot;embedded&quot;: true, &quot;decorated&quot;: false }, 200, 200, {} ]</span>
<span class="n">rows</span> <span class="mi">20</span> 
<span class="n">cols</span> <span class="mi">20</span> 
<span class="n">players</span> <span class="mi">2</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">.....</span><span class="o">*</span><span class="p">..............</span>
<span class="n">m</span> <span class="p">......</span><span class="o">%</span><span class="p">..</span><span class="n">b</span><span class="p">..........</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">........</span><span class="n">aa</span><span class="p">..........</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
</pre></div>


<p>Below is sample output from player 'a' in the above game:</p>
<div class="codehilite"><pre><span class="n">go</span>  
<span class="n">o</span> <span class="mi">10</span> <span class="mi">8</span> <span class="n">N</span> 
<span class="n">o</span> <span class="mi">10</span> <span class="mi">9</span> <span class="n">N</span>
<span class="n">go</span>
</pre></div>


<p>Below is sample output from player 'b' in the above game:</p>
<div class="codehilite"><pre><span class="n">go</span>  
<span class="n">o</span> <span class="mi">7</span> <span class="mi">9</span> <span class="n">W</span> 
<span class="n">go</span>
</pre></div>


<h3 id="blocking">Blocking</h3>
<p>Only movement is blocked by water. Ants can see and attack over water. If a bot issues a move for an ant onto water, it is considered an invalid move and therefore ignored.</p>
<p>Food will also block an ants movement.  This can happen if food spawns next to an ant.  Don't move the ant and it will be gathered the next turn.</p>
<h3 id="collisions">Collisions</h3>
<p>You can order 2 ants to the same space.  If you do this, both ants will die.  You can order your ant to go to the same space as an enemy ant, both ants will die before the attack radius is considered.  This can happen if an ant spawns near an enemy.  (You might also be close to losing your hill if enemies are right next to where you spawn!)</p>
<!--</MarkdownReplacement>-->
</div>

<div id="Map-Format">
<!--<MarkdownReplacement with="competition-Map-Format.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="map-format">Map Format</h2>
<p>A map consists of a rectangular grid. Each square can contain land, water, food, a live ant, multiple dead ants or an ant hill.  Ants can also be on top of their own hill. The edges of the map are wrapped, meaning if you walk off the top of the map, you will appear at the bottom, or if you walk off the right, you will appear on the left, assuming water doesn't block your path.</p>
<p>A map file is like an ordinary text file, except the extension is ".map" rather than ".txt", with the following format:</p>
<div class="codehilite"><pre><span class="n">rows</span> <span class="n">noRows</span>
<span class="n">cols</span> <span class="n">noCols</span>
<span class="n">players</span> <span class="n">noPlayers</span>
<span class="n">score</span> <span class="n">s1</span> <span class="n">s2</span> <span class="p">...</span>
<span class="n">hive</span> <span class="n">h1</span> <span class="n">h2</span> <span class="p">...</span>
<span class="n">m</span> <span class="p">[.</span><span class="o">%*!?</span><span class="n">a</span><span class="o">-</span><span class="n">jA</span><span class="o">-</span><span class="n">J0</span><span class="o">-</span><span class="mi">9</span><span class="p">]</span>
</pre></div>


<p>The symbols used have the following meaning:</p>
<div class="codehilite"><pre><span class="p">.</span>   <span class="o">=</span> <span class="n">land</span>
<span class="o">%</span>   <span class="o">=</span> <span class="n">water</span>
<span class="o">*</span>   <span class="o">=</span> <span class="n">food</span>
<span class="o">!</span>   <span class="o">=</span> <span class="n">dead</span> <span class="n">ant</span> <span class="n">or</span> <span class="n">ants</span>
<span class="o">?</span>   <span class="o">=</span> <span class="n">unseen</span> <span class="n">territory</span>
<span class="n">a</span><span class="o">-</span><span class="n">j</span> <span class="o">=</span> <span class="n">ant</span>
<span class="n">A</span><span class="o">-</span><span class="n">J</span> <span class="o">=</span> <span class="n">ant</span> <span class="n">on</span> <span class="n">its</span> <span class="n">own</span> <span class="n">hill</span>
<span class="mi">0</span><span class="o">-</span><span class="mi">9</span> <span class="o">=</span> <span class="n">hill</span>
</pre></div>


<p>Maps can almost describe the start of any turn of the game, except for multiple dead ants on the same square and who the owner(s) are.</p>
<p>For running games, the maps generated only need describe the start of the game and use only a subset of the full map specification.  Food, ants, dead ants are thrown out.  No square should be unseen.  No score or hive amounts should be included.  Maps for games should be symmetric.</p>
<p><strong>Sample Map:</strong></p>
<div class="codehilite"><pre><span class="n">rows</span> <span class="mi">20</span> 
<span class="n">cols</span> <span class="mi">20</span> 
<span class="n">players</span> <span class="mi">2</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">.....</span><span class="o">*</span><span class="p">..............</span>
<span class="n">m</span> <span class="p">......</span><span class="o">%</span><span class="p">..</span><span class="n">b</span><span class="p">..........</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">........</span><span class="n">aa</span><span class="p">..........</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
<span class="n">m</span> <span class="p">....................</span>
</pre></div>


<h2 id="map-generators">Map Generators</h2>
<p>Maps are generated randomly using a program which is designed to try and result in interesting games while not allowing for people to hard code strategies.</p>
<ul>
<li>Maps are limited to 2 to 10 players</li>
<li>Maps must be symmetric</li>
<li>Hills must be between 20 and 150 steps away from other enemy hills (friendly hills can be closer)</li>
<li>Hills may not be within close range, euclidean distance no less than 6</li>
<li>Must be a path through all hills traversable by a 3x3 block</li>
<li>Maps must not contain islands</li>
<li>Maps are limited to at most 200 in each direction</li>
<li>Maps are limited in area to 900 to 5000 area per player, with a total area limit of 25,000.</li>
</ul>
<p>There are currently no further restrictions on what form the map generator for the final contest will take.  New map generators will be welcomed by anyone who wishes to write one.  Generators should do the following:</p>
<ul>
<li>Produce a map with the above qualities</li>
<li>Can be written in any language</li>
</ul>
<!--</MarkdownReplacement>-->
</div>

<div id="Replay-Format">
<!--<MarkdownReplacement with="competition-Replay-Format.md">--><style>img.latex-inline { vertical-align: middle; }</style>
<h2 id="formats-in-use">Formats in use</h2>
<p>There are two formats the engine can write. The first format is a streaming format that outputs data turn by turn. It is about ten times larger, but can be used to view games in progress (see 'Streaming format' below). The other format is used to store replays on the disk (see 'Storage format' below).</p>
<h3 id="storage-format">Storage format</h3>
<p>Replays you can download from servers are likely the storage format which is replay data and meta data in JavaScript object notation (JSON). Here is an example file (with the replay data truncated):</p>
<div class="codehilite"><pre><span class="p">{</span>
    <span class="s">&quot;challenge&quot;</span><span class="o">:</span> <span class="s">&quot;ants&quot;</span><span class="p">,</span>
    <span class="s">&quot;date&quot;</span><span class="o">:</span> <span class="s">&quot;11-11-1111&quot;</span><span class="p">,</span>
    <span class="s">&quot;playernames&quot;</span><span class="o">:</span> <span class="p">[</span><span class="s">&quot;amstan&quot;</span><span class="p">,</span> <span class="s">&quot;a1k0n&quot;</span><span class="p">,</span> <span class="s">&quot;mega1&quot;</span><span class="p">],</span>
    <span class="s">&quot;playerstatus&quot;</span><span class="o">:</span> <span class="p">[</span><span class="s">&quot;timeout&quot;</span><span class="p">,</span> <span class="s">&quot;crash&quot;</span><span class="p">,</span> <span class="s">&quot;Some other message&quot;</span><span class="p">],</span>
    <span class="s">&quot;submitids&quot;</span><span class="o">:</span> <span class="p">[</span><span class="mi">6</span><span class="p">,</span> <span class="mi">3</span><span class="p">,</span> <span class="mi">7</span><span class="p">],</span>
    <span class="s">&quot;user_ids&quot;</span><span class="o">:</span> <span class="p">[</span><span class="mi">94</span><span class="p">,</span> <span class="mi">813</span><span class="p">,</span> <span class="mi">39</span><span class="p">],</span>
    <span class="s">&quot;user_url&quot;</span><span class="o">:</span> <span class="s">&quot;http://b2ki.science.uu.nl/profile.php?user_id=~&quot;</span><span class="p">,</span>
    <span class="s">&quot;game_id&quot;</span><span class="o">:</span> <span class="s">&quot;12345&quot;</span><span class="p">,</span>
    <span class="s">&quot;game_url&quot;</span><span class="o">:</span> <span class="s">&quot;http://b2ki.science.uu.nl/ants/visualizer.php?game_id=~&quot;</span><span class="p">,</span>
    <span class="s">&quot;replayformat&quot;</span><span class="o">:</span> <span class="s">&quot;json&quot;</span><span class="p">,</span>
    <span class="s">&quot;replaydata&quot;</span><span class="o">:</span> <span class="p">{</span>
        <span class="s">&quot;revision&quot;</span><span class="o">:</span> <span class="mi">2</span><span class="p">,</span>
        <span class="s">&quot;players&quot;</span><span class="o">:</span> <span class="mi">3</span><span class="p">,</span>
        <span class="s">&quot;turns&quot;</span><span class="o">:</span> <span class="mi">200</span><span class="p">,</span>
        <span class="o">&lt;</span><span class="p">...</span><span class="o">&gt;</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>


<h3 id="meta-data">Meta data</h3>
<p>This format was designed to be future proof, so it contains additional information to distinguish it from other replays as well as meta data</p>
<ul>
<li><em>challenge</em> is the name of the challenge or game that this replay is for. If it doesn't read "ants" you don't need to parse anything else.</li>
<li><em>replayformat</em> for ants should be "json"</li>
<li><em>replaydata</em> is the replay in the format set by "replayformat". As only "json" is in use, the replay data will automatically be parsed by your JSON library of choice.</li>
<li><em>user_url</em> and <em>user_ids</em> can be used to get a link to each user's page on the respective server. The ~ is replaced by the id.</li>
<li><em>game_url</em> and <em>game_id</em> can be used to find the original game.</li>
<li><em>playerstatus</em> explains what happened to a player after its last turn. The status could be any string with spaces, but the following predefined strings should be used it appropriate: 'timeout' (could be displayed as '... did not respond in time' or '... timed out') = the bot did not respond in time and was disqualified, 'crash' = the bot program crashed, 'eliminated' = no ants survived. 'survived' = the bot survived to the end of the game. Other status messages could also be used and must be displayed literally. E.g.: 'The bot tried to install a root kit'.</li>
<li><em>submitids</em> Each ant bot code submitted to the contest is assigned a unique id. For reference this can be included in the metadata. In case player's submissions are made available after the contest, the id from a downloaded replay can be used to find the code.</li>
<li><em>playercolors \&lt;array of html color codes></em> The replay file can set player colors in html notation. This is either #rrggbb or #rgb where r, g and b are upper- or lowercase hexadecimal digits. A visualizer must be prepared to select default colors for the players if the replay lacks them. An example can be found here [[http://martin.ankerl.com/2009/12/09/how-to-create-random-colors-programmatically/]]
same color.</li>
<li><em>antvalue \&lt;bounty></em> The score value for a dying ant.</li>
</ul>
<p>A visualizer is required to understand and check the "challenge" key, then check the "replayformat" and finally read the "replaydata". The other keys are optional information.</p>
<h3 id="replay-data">Replay data</h3>
<p>The "replaydata" field is an object made up of a list of all ants in the game, the map, the scores for each player and turn and some game settings.</p>
<ul>
<li><em>revision 2</em> The revision of this specification that was used to generate the replay.</li>
<li><em>players \&lt;number></em> This sets up the number of players in the replay. It can be anywhere from 1 to 26.</li>
<li><em>loadtime \&lt;time in ms></em> This time was given to the bot executables to load up before the match started.</li>
<li><em>turntime \&lt;time in ms></em> This time was given to the bot for each turn.</li>
<li><em>turns \&lt;number></em> The turn limit that was set for the match, which may have ended earlier.</li>
<li><em>viewradius2 \&lt;number></em> The squared ant view radius.</li>
<li><em>attackradius2 \&lt;number></em> The squared attack radius.</li>
<li><em>spawnradius2 \&lt;number></em> The squared spawn radius.</li>
<li><em>\&lt;parameter name> \&lt;value></em> Other parameters can have arbitrary names and are meant for an easy extension of the format.</li>
<li><em>map &lt;object&gt;</em> This object contains the map.<ul>
<li><em>rows \&lt;number></em> The number of rows in the map.</li>
<li><em>cols \&lt;number></em> The number of columns in the map.</li>
<li><em>data \&lt;array></em> An array of strings, one for each row. Every string in the array must contain as many characters as there are columns. The used characters in maps are: . = land, % = water, * = food. If a player has a starting ant, instead of '.' the map will show a letter from 'a' to 'z' = player 1 to 26. These spawns and the food locations are not authorative for the visualizer, but can be used as a preview in a replay browser application. That said a visualizer should assume all squares to be land if their character is not '%'.</li>
</ul>
</li>
<li><em>ants \&lt;array></em> An array of all ants in the game. Food and the ant it is eventually converted into is also a single element of this array. Food can be considered an ant which doesn't have an owner yet. There are some cases to be considered: Some food is never converted, some ants are there from the beginning of the game, some ants survive, some food could be removed from the game if the rules allow two enemy ants to come close enough to the food item. Each element of the ants array is itself an array with either 4 elements (food, which is not converted) or 7 elements: <em>\&lt;row> \&lt;col> \&lt;start turn> \&lt;conversion turn> \&lt;end turn> \&lt;player> \&lt;moves></em> Each object has an initial \&lt;row> and \&lt;col> on the map as well as the \&lt;start turn> in which they first appear on the map. For the \&lt;conversion turn> and the \&lt;end turn> there is a special rule that makes parsing easier: The turn number is the total number of turns +1 if the food or ant survives the game. The \&lt;conversion turn> tells the visualizer when the food is converted into an ant or disappears from the map. The parameter list may end here if the food is not converted to a player (i.e. it disappears or survives the whole game). If the food is converted, the following parameters exist. \&lt;end turn> is basically the same as \&lt;conversion turn>. It is either total turns +1 or the turn in which the ant is dead. \&lt;player> is the 0 based player number and \&lt;moves> is a string of commands issued by the bot for the ant. Each character is either '-' = do nothing or a move order ('n', 'e', 's', 'w') for a turn starting with \&lt;conversion turn>. Note: For ants that are there from the beginning of the game both \&lt;start turn> and &lt;\conversion turn> are 0!</li>
<li><em>scores \&lt;array></em> There will be exactly as elements in this array as there are <em>players</em>. The first element is for player 1, the second for player 2 and so on. Each element is an array of floating point score values, where each value represents the player's score for the start of a turn. If a player crashed before completing its first turn there will be one score entry (usually 0). If a player survived a 200 turns game, there will be 201 values including the end game score. A reason why a certain player did not make it to the end may be given in the meta data.</li>
<li><em>bonus \&lt;array></em> An optional field that holds the "food bonus" value for each player. Any number which is not 0 is added to the score after the last turn in the game.</li>
</ul>
<h3 id="streaming-format">Streaming format</h3>
<p>The streaming format can be used to visualize games in progress. It produces a lot more data than the storage format, but has the benefit of being turn based. That means that each turn can be visualized as soon as the engine completed it.
Details of the format follow...</p>
<!--</MarkdownReplacement>-->
</div>


<?php

require_once('visualizer_widget.php');
visualize_pre();
require_once('footer.php');

?>
