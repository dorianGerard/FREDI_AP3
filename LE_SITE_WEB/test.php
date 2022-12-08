<?php 
    include 'functions/dbconnect.php';
    $laDATA = db_connect();
    $mdp = "5cYqqgMn3unc9tA";
    echo "<p>1. ".password_hash($mdp, PASSWORD_BCRYPT)."</p>";
    echo "<p>2. ".password_hash($mdp, PASSWORD_BCRYPT)."</p>";

?>
