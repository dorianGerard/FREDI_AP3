<?php session_start(); ?>
<link rel="stylesheet" href="css/styles.css">
<header class="header">
  <a href="index.php" class="logo">Accueil</a>

  <input class="menu-btn" type="checkbox" id="menu-btn" />
  <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>

  <ul class="menu">

    <li class="titre"><a href="index.php">FREDI</a></li>
    <?php
      if (isset($_SESSION['roleid']))
      {
        if ($_SESSION['roleid'] == 1) {
          echo "<li><a href='frais.php'>Mes notes de frais</a></li>";
        }
        if ($_SESSION['roleid'] == 2) {
          echo "<li><a href='controleur.php'>Contrôleur</a></li>";
          echo "<li><a href='noteFraisControleur.php'>Les notes de frais</a></li>";
        }
        if($_SESSION['roleid'] == 3) {
        echo "<li><a href='utilisateurs.php'>Liste des utilisateurs</a></li>";
        }
      echo "<li style='width: 300px;'><a href='' style='padding-top: 11px;padding-bottom: 0px;'>Bienvenue " . $_SESSION['pseudo'] . ", vous êtes connecté en tant que " . $_SESSION['rolelib'] . "</a></li>";
      echo "<li><a href='deco.php'>Déconnexion</a></li>";
      } else {
      echo "<li><a href='connexion.php'>Connectez-vous</a></li>";
    } ?>
  </ul>

</header>