<?php

$title = "Home";
include('header.php');
require_once('memcache.php');

if(file_exists('server_message.html')) {
    //Used to convey a message on the front page
    include('server_message.html');
}

?>

<h1>Welcome to the B2KI AI Challenge!</h1>
On this page you will find all the information you need to get started with the practical sessions of the B2KI course. 
	Whoever wishes to show of his ant battling skills can also submit his bot in the online competition.
Keep in mind though that your position in the ranking will not influence your grade!

<h1>Replay of the latest game:</h1>
<?php
    $last_game_id = 0;
    if ($memcache)
        $last_game_id = $memcache->get('l:splash');
    if (!$last_game_id) {
        $last_game_id = 0;
    }
    include 'visualizer_widget.php';
    visualize_game($game_id=strval($last_game_id),false,700,700);
?>

<p>Browse other <a href="games.php">recent games here</a>.</p>

<?php include 'footer.php'; ?>
