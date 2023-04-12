<?php

    include "functions/dbconnect.php";
    session_start();
    $laDATA = db_connect();

    try {
        $sql = "SELECT pseudo FROM utilisateur WHERE role = 1";
        $result = $laDATA->prepare($sql);
        $result->execute();
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Les notes de frais</title>
</head>
<body>  
    <div class="logCSS">
        <p>Choississez l'utilisateur auquel vous souhaitez voir les notes de frais :</p>
        <p>Retournez à l'<a href="index.php">accueil</a>.</p>
        <form action="noteFraisControleur.php" method="POST">
            <select name="user">
                <?php
                    foreach($row as $rows)
                    {
                        echo "<option value='".$rows['pseudo']."'>".$rows['pseudo']."</option>";
                    }
                ?>
            </select>
            <input class="button-3" type="submit" name="submitNote" value="Envoyer">
        </form>
    </div>
    
<?php
    // Note de frais
    if(isset($_POST['submitNote']))
    {
        try {
            $sql = "SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo";
            $result = $laDATA->prepare($sql);
            $result->execute(array(":pseudo" => $_POST['user']));
            $id = $result->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT * FROM note WHERE id_utilisateur = :id_utilisateur";
            $result = $laDATA->prepare($sql);
            $result->execute(array(":id_utilisateur" => $id['id_utilisateur']));
            $row = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL : " . $ex->getMessage());
        }
        echo "<div class='logCSS'>";
        echo "<table class='UserList'>";
        echo "<tr class='UserList'>";
        echo "<th class='UserList2'>ID Note</th>";
        echo "<th class='UserList2'>est valide ?</th>";
        echo "<th class='UserList2'>Montant Totale</th>";
        echo "<th class='UserList2'>Date remise</th>";
        echo "<th class='UserList2'> </th>";
        echo "</tr>";
        foreach($row as $rows)
        {
            echo "<tr class='UserList'>";
            echo "<td class='UserList2'>".$rows['id_note']."</td>";
            echo "<td class='UserList2'>".$rows['est_valide']."</td>";
            echo "<td class='UserList2'>".$rows['mt_total']."</td>";
            echo "<td class='UserList2'>".$rows['dat_remise']."</td>";
            echo "<td class='UserList2'><form action='noteFraisControleur.php' method='POST'><input class='button-3' type='submit' name='submitLigne' value='Détails'><input type='hidden' name='idNote' value='".$rows['id_note']."'></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    
    // Ligne de frais selon Note de Frais choisi
    if(isset($_POST['submitLigne']))
    {
        try {
            $sql = "SELECT * FROM ligne WHERE id_note = :id_note";
            $result = $laDATA->prepare($sql);
            $result->execute(array(":id_note" => $_POST['idNote']));
            $row = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL : " . $ex->getMessage());
        }
        echo "<div class='logCSS'>";
        echo "<table class='UserList'>";
        echo "<tr class='UserList'>";
        echo "<th class='UserList2'>ID Ligne</th>";
        echo "<th class='UserList2'>Date</th>";
        echo "<th class='UserList2'>Libelle Trajet</th>";
        echo "<th class='UserList2'>Nombre Km</th>";
        echo "<th class='UserList2'>Montant Péage</th>";
        echo "<th class='UserList2'>Montant Repas</th>";
        echo "<th class='UserList2'>Montant Hebergement</th>";
        echo "<th class='UserList2'>Montant Total</th>";
        echo "<th class='UserList2'>ID Motif</th>";
        echo "</tr>";
        foreach ($row as $rows) {
            echo "<tr class='UserList'>";
            echo "<td class='UserList2'>" . $rows['id_ligne'] . "</td>";
            echo "<td class='UserList2'>" . $rows['date_ligne'] . "</td>";
            echo "<td class='UserList2'>" . $rows['lib_trajet'] . "</td>";
            echo "<td class='UserList2'>" . $rows['nb_km'] . "</td>";
            echo "<td class='UserList2'>" . $rows['mt_peage'] . "</td>";
            echo "<td class='UserList2'>" . $rows['mt_repas'] . "</td>";
            echo "<td class='UserList2'>" . $rows['mt_hebergement'] . "</td>";
            echo "<td class='UserList2'>" . $rows['mt_total'] . "</td>";
            echo "<td class='UserList2'>" . $rows['id_motif'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } 
    
?>
</body>
</html>



