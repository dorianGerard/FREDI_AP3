<?php
//include des fonctions de la bdd
// include "menu.php";
    include "functions/dbconnect.php";
    //initialisation de la session
    session_start();
    //connexion à la bdd
    $dbh=db_connect();

//récupération de la liste des utilisateurs et de leurs role
    $sql = 'select * from utilisateur';
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }
    foreach ($rows as $row){
        $role=$row['role'];
    
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
            echo "<td class='UserList2'>".$role."</td>";
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