<?php

include "functions/dbconnect.php";
session_start();
$laDATA = db_connect();
$submit = isset($_POST['submit']);

// L’administrateur et le controleur n'ont pas le droit de créer de note de frais
if ($_SESSION['roleid'] === 3 || $_SESSION['roleid'] === 2) {
    header("Location: index.php");
}

// si c'est un utilisateur ou un controleur, alors on se limite à ses notes seulement
if ($_SESSION['roleid'] != 3) {
    $sql = "SELECT * FROM note, utilisateur WHERE note.id_utilisateur = :id_user
    AND note.id_utilisateur=utilisateur.id_utilisateur";
    $result = $laDATA->prepare($sql);
    $result->execute(array(":id_user" => $_SESSION['id']));
} else {
    $sql = "SELECT * FROM note";
    $result = $laDATA->prepare($sql);
    $result->execute();
}
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
        foreach ($row as $rows) {
            echo "<tr class='UserList'>";
            echo "<td class='UserList2'>" . $rows['id_note'] . "</td>";
            echo "<td class='UserList2'>" . $rows['est_valide'] . "</td>";
            echo "<td class='UserList2'>" . $rows['mt_total'] . " €</td>";
            echo "<td class='UserList2'>" . $rows['dat_remise'] . "</td>";
            echo "<td class='UserList2'>" . $rows['id_periode'] . "</td>";
            echo "<td>";
            echo "<form action='frais.php' method='POST'>";
            echo "<input class='button-3' type='submit' name='submit' value='Détail'>";
            echo "<input type='hidden' name='estValide' value='".$rows['est_valide']."'>";
            echo "<input type='text' name='idnote' style='display:none;' value='" . $rows['id_note'] . "'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
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
            if ($_SESSION['roleid'] === 1 && $_POST['estValide'] == '0') {
                echo "<th class='UserList2' colspan='3'>Autres</th>";
            }
            echo        "</tr>";

            $idNote = isset($_POST['idnote']) ? trim($_POST['idnote']) : NULL;
            //echo $idNote;
            try {
                $sql = "SELECT * FROM ligne WHERE id_note = :id_note";
                $result = $laDATA->prepare($sql);
                $result->execute(array(":id_note" => $idNote));
                $row = $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $ex) {
                die("Erreur lors de la requête SQL : " . $ex->getMessage());
            }
            foreach ($row as $rows) {
                echo "<tr class='UserList'>";
                echo "<td>" . $rows['id_ligne'] . "</td>";
                echo "<td>" . $rows['date_ligne'] . "</td>";
                echo "<td>" . $rows['lib_trajet'] . "</td>";
                echo "<td>" . $rows['nb_km'] . "</td>";
                echo "<td>" . $rows['mt_peage'] . "</td>";
                echo "<td>" . $rows['mt_repas'] . "</td>";
                echo "<td>" . $rows['mt_hebergement'] . "</td>";
                echo "<td>" . $rows['mt_total'] . "</td>";
                echo "<td>" . $rows['id_motif'] . "</td>";

                if ($_SESSION['roleid'] === 1 && $_POST['estValide'] == '0') {
                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/addFraisLigne.php?id_note=" . $rows['id_note'] . "' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input class='button-3' name='add' Value='Ajouter' type='submit'/>";
                    echo "</form>";
                    echo "</td>";

                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/editFraisLigne.php' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input class='button-3' type='submit' name='edit' Value='Modifier' />";
                    echo "</form>";
                    echo "</td>";

                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/deleteFraisLigne.php?id_ligne=" . $rows['id_ligne'] . "' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input class='button-3' name='Delete' Value='Supprimer' type='submit'/>";
                    echo "</form>";
                    echo "</td>";
                }

                echo "</tr>";
            }
        }
        ?>
    </table>
    <p>Ajouter une <a href="addFrais.php">note de frais</a></p>
    <p>Obtenir le pdf <a href=" cumul_des_frais_pdf.php">du cumul des frais</a></p>
    <p>Obtenir le <a href='bordereau_pdf.php'>Borderau</a> (connecté en tant que <?= $_SESSION['pseudo']?>)</p>
    <p>Aller à <a href="index.php">l'accueil</a></p>
</body>

</html>