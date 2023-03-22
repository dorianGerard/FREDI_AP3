<?php

include "functions/dbconnect.php";
//include "functions/menu.php";
session_start();
$laDATA = db_connect();
$submit = isset($_POST['submit']);

// L’administrateur n’a pas accès aux notes.
if ($_SESSION['roleid'] === 3) {
    header("Location: index.php");
}

// si c'est un utilisateur ou un controleur, alors on se limite à ses notes seulement
if ($_SESSION['roleid'] != 3) {
    $sql = "SELECT * from note, periode, utilisateur
    WHERE periode.est_active=1
    AND note.id_periode=periode.id_periode
    AND utilisateur.id_utilisateur=note.id_utilisateur";
    $result = $laDATA->prepare($sql);
    $result->execute();
} else {
    echo "Vous n'êtes pas autorisé à accéder à cette ressource.";
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
    <title>Contrôleur</title>
</head>

<body>

<br/>
<p>Tableau des périodes:</p>
<br/>
<table class="UserList">
<tr class="UserList">
<th class="UserList2">Libellé de la période</th>
<th class="UserList2">Etat de la période</th>
<th class="UserList2">Autres</th>
</tr>
<?php
$liste_periode="SELECT * from periode";
$liste = $laDATA->prepare($liste_periode);
$liste->execute();
$periode = $liste->fetchall(PDO::FETCH_ASSOC);

foreach($periode as $periodes){
    echo "<tr class='UserList'>";
    echo "<td class='UserList2'>" . $periodes['lib_periode'] . "</td>";
    switch ($periodes['est_active']) {
        case 1:
            echo "<td class='UserList2'>Active, en cours</td>";
            break;
        case 0:
            echo "<td class='UserList2'>Inactive</td>";
            break;
    }
    echo "<td class='UserList2'><form action='periode.php' method='POST'><input type='submit' class='button-3' value='Activer' name='activePeriode'>";
    echo "<input type='hidden' value='".$periodes['id_periode']."' name='idperiode'></form></td>";
    echo "</tr>";
}
?>
</table>
<br/>
<form action="periode.php" method="POST">
    <input type="text" class="logCSS" name="libPeriode" placeholder="Libellé Periode" required>
    <br/>
    <br/>
    <input type="number" class="logCSS" name="mtKmPeriode" placeholder="Montant par KM" required>
    <br/>
    <br/>
    <input type="submit" class="button-3" value="Créer période" name="createPeriode">
    <br/>
    <br/>
</form>


<!-- On récupère la première occurence [0], ça fonctionne car seule une période est active à la fois -->
<br/>
<br/>
<?php

    if(isset($row[0]['lib_periode']))
    {
        echo "<p>Notes de frais de la ".$row[0]['lib_periode']."</p>";
    }

?>

<br/>
<table>
    <table class="UserList">
        <tr class="UserList">
            <th class="UserList2">Nom de l'utilisateur</th>
            <th class="UserList2">ID Note</th>
            <th class="UserList2">Validité</th>
            <th class="UserList2">Montant Total</th>
            <th class="UserList2">Date Remise</th>
            <th class="UserList2">Numéro ordre</th>

        </tr>
        <?php
        foreach ($row as $rows) {
            if($rows['est_valide'] == '0') {
            echo "<tr class='UserList'>";
            echo "<td class='UserList2'>" . $rows['pseudo'] . "</td>";
            echo "<td class='UserList2'>" . $rows['id_note'] . "</td>";
            echo "<td class='UserList2'>En attente de validation</td>";
            echo "<td class='UserList2'>" . $rows['mt_total'] . " </td>";
            echo "<td class='UserList2'>" . $rows['dat_remise'] . "</td>";
            echo "<td class='UserList2'>" . $rows['id_periode'] . "</td>";
            echo "<td>";
            echo "<form action='valider.php' method='POST'>";
            echo "<input type='submit' class='button-3' name='submitDetails' value='Details'>";
            echo "<input type='text' name='idnote' style='display:none;' value='" . $rows['id_note'] . "'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            }

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
            if ($_SESSION['roleid'] === 1) {
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

                if ($_SESSION['roleid'] === 1) {
                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/addFraisLigne.php?id_note=" . $rows['id_note'] . "' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input name='add' Value='Ajouter' type='submit'/>";
                    echo "</form>";
                    echo "</td>";

                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/editFraisLigne.php' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input type='submit' name='edit' Value='Modifier' />";
                    echo "</form>";
                    echo "</td>";

                    echo "<td>";
                    echo "<form action='./CRUD_Ligne/deleteFraisLigne.php?id_ligne=" . $rows['id_ligne'] . "' method='POST'>";
                    echo "<input type='text' name='idLigne' style='display:none;' value='" . $rows['id_ligne'] . "'>";
                    echo "<input name='Delete' Value='Supprimer' type='submit'/>";
                    echo "</form>";
                    echo "</td>";
                }

                echo "</tr>";
            }
        }
        ?>
    </table>
    <p>Obtenir le pdf <a href=" cumul_des_frais_pdf.php">du cumul des frais</a></p>
    <p>Aller à <a href="index.php">l'accueil</a></p>
</body>

</html>
