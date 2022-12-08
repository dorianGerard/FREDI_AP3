<?php 

    include "functions/dbconnect.php";
    session_start();
    $laDATA = db_connect();
    $submit = isset($_POST['submit']);

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
                echo "<tr class='UserList'><td class='UserList2'>".$rows['id_note']."</td><td class='UserList2'>".$rows['est_valide']."</td><td class='UserList2'>".$rows['mt_total']." €</td><td class='UserList2'>".$rows['dat_remise']."</td><td class='UserList2'>".$rows['id_periode']."</td><td><form action='frais.php' method='POST'><input type='submit' name='submit' value='Détail'><input type='text' name='idnote' style='display:none;' value='".$rows['id_note']."'></form></td></tr>";
            }
            if ($submit) {
                echo    "</table>";
                echo    "<table class='UserList'>";
                echo        "<tr class='UserList'>";
                echo            "<th class='UserList2'>Id</th>";
                echo            "<th class='UserList2'>Date</th>";
                echo            "<th class='UserList2'>Nom</th>";
                echo            "<th class='UserList2'>Nombre KM</th>";
                echo            "<th class='UserList2'>Montant péage</th>";
                echo            "<th class='UserList2'>Montant repas</th>";
                echo            "<th class='UserList2'>Montant Hébergement</th>";
                echo            "<th class='UserList2'>Total</th>";
                echo            "<th class='UserList2'>ID Motif</th>";
                echo            "<th class='UserList2'>Autres</th>";
                echo        "</tr>";

                $idNote = isset($_POST['idnote']) ? trim($_POST['idnote']) : NULL;
                echo $idNote;
                try {
                    $sql = "SELECT * FROM ligne WHERE id_note = :id_note";
                    $result = $laDATA -> prepare($sql);
                    $result->execute(array(":id_note" => $idNote));
                    $row = $result->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $ex) {
                    die("Erreur lors de la requête SQL : " . $ex->getMessage());
                }
                foreach($row as $rows) {
                echo "<tr class='UserList'><td>".$rows['id_ligne']."</td><td>".$rows['date_ligne']."</td><td>".$rows['lib_trajet']."</td><td>".$rows['nb_km']."</td><td>".$rows['mt_peage']."</td><td>".$rows['mt_repas']."</td><td>".$rows['mt_hebergement']."</td><td>".$rows['mt_total']."</td><td>".$rows['id_motif']."</td><td><form action='./CRUD_Ligne/addFraisLigne.php' method='POST'><input type='text' name='idLigne' style='display:none;' value='".$rows['id_ligne']."'><input name='add' Value='Ajouter' type='submit'/></form></td><td><form action='./CRUD_Ligne/editFraisLigne.php' method='POST'><input type='text' name='idLigne' style='display:none;' value='".$rows['id_ligne']."'><input type='submit' name='edit' Value='Modifier' /></form></td><td><form action='./CRUD_Ligne/deleteFraisLigne.php' method='POST'><input type='text' name='idLigne' style='display:none;' value='".$rows['id_ligne']."'><input name='Delete' Value='Supprimer' type='submit'/></form></td></tr>";
            }
        }
        ?>
    </table>
    <p>Ajouter une <a href="addFrais.php">note de frais</a></p>
    <p>Aller à <a href="index.php">l'accueil</a></p>
</body>
</html>
