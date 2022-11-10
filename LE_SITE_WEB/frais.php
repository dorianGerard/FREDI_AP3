<?php 

    include "functions/dbconnect.php";
    session_start();
    $laDATA = db_connect();

    $sql = "SELECT * FROM note WHERE id_utilisateur = :id_user";
    $result = $laDATA -> prepare($sql);
    $result->execute(array(":id_user" => $_SESSION['id']));
    $row = $result->fetchall(PDO::FETCH_ASSOC);
    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Note de frais</title>
</head>
<body>
    <table class="UserList">
        <tr class="UserList">
            <th class="UserList2">ID Note</th>
            <th class="UserList2">Valide</th>
            <th class="UserList2">Montant Total</th>
            <th class="UserList2">Date Remise</th>
            <th class="UserList2">Numéro ordre</th>
        </tr>
            <?php 
            foreach($row as $rows)
            {
                echo "<tr class='UserList'><td class='UserList2'>".$rows['id_note']."</td><td class='UserList2'>".$rows['est_valide']."</td><td class='UserList2'>".$rows['mt_total']." €</td><td class='UserList2'>".$rows['dat_remise']."</td><td class='UserList2'>".$rows['id_periode']."</td></tr>";
            }
            ?>
    </table>
    <p>Ajouter une <a href="addFrais.php">note de frais</a></p>
    <p>Aller à <a href="index.php">l'accueil</a></p>
</body>
</html>
