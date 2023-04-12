<?php
//include des fonctions de la bdd
// include "menu.php";
    include "functions/dbconnect.php";
    //initialisation de la session
    session_start();
    //connexion à la bdd
    $dbh=db_connect();

if ($_SESSION['roleid'] === 1 || $_SESSION['roleid'] === 2) {
    header("Location: index.php");
}

//récupération de la liste des utilisateurs et de leurs role
    $sql = 'select * from utilisateur';
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
 
    <title>Gestion des utilisateurs</title>
</head>
<body>
    <h1>Utilisateurs</h1>
<table class="UserList">
    <tr>
      <th class='UserList2'>Prenom</th>
      <th class='UserList2'>Nom</th>
      <th class='UserList2'>Mail</th>
      <th class='UserList2'>Pseudo</th>
      <th class='UserList2'>Role</th>
    </tr>
    <?php
        foreach ($rows as $row) {
            echo "<tr class='UserList'>";
            echo "<td class='UserList2'>".$row["prenom"]."</td>";
            echo "<td class='UserList2'>".$row["nom"]."</td>";
            echo "<td class='UserList2'>".$row["mail"]."</td>";
            echo "<td class='UserList2'>".$row["pseudo"]."</td>";
            switch ($row["role"]) {
                case 1:
                    echo "<td class='UserList2'>Utilisateur</td>";
                    break;
                case 2:
                    echo "<td class='UserList2'>Contrôleur</td>";
                    break;
                case 3:
                    echo "<td class='UserList2'>Administrateur</td>";
                    break;
            }
            echo "</tr>";
        }
    ?>
  </table>
  <?php
    // Nombre d'utilisateurs
    echo "<p>Il y a ".count($rows). " utilisateur(s)</p>";
    ?>

    <p>Retournez à <a href="index.php">l'accueil</a></p>
</body>
</html>
