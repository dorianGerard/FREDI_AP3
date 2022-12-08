<?php 

// Detruit tous et on repart sur l'index
session_start();
session_unset();
session_destroy();
header("Location: index.php");
?>