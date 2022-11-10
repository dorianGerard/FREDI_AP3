<?php
//
session_start();
// include "inc/db_functions.php";

// Faire une condition avec la SESSION / COOKIE pour faire apparaitre le message ligne 22 ou pas.
?>
<link rel="stylesheet" href="css/styles.css">
<header class="header">
<a href="" class="logo">Accueil</a>
  
  <input class="menu-btn" type="checkbox" id="menu-btn" />
  <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
  
  <ul class="menu">

    <li class="titre"><a href="">FREDI</a></li>
    <?php
    if($_SESSION) {
    echo "<li><a href='utilisateurs.php'>Liste des utilisateurs</a></li>";
    echo "<li><a href='frais.php'>Note de frais</a></li>";
    echo "<li><a href=''>Bienvenue ".$_SESSION['pseudo'].", vous êtes connecté en tant que ".$_SESSION['rolelib']."</a></li>";
    echo "<li><a href='deco.php'>Déconnexion</a></li>"; }
    else {
      echo "<li><a href='connexion.php'>Connectez-vous</a></li>";
    } ?>
  </ul>
 
</header>
