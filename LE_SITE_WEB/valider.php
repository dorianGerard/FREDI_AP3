<?php

include "functions/dbconnect.php";
$laDATA = db_connect();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details note de frais</title>
</head>
<body>
   <?php

    if(isset($_POST['submitDetails']))
    {

        
        // header("Location: valider.php");
        $idNote = isset($_POST['idnote']) ? trim($_POST['idnote']) : NULL;
            //echo $idNote;
            try {
                $sql1 = "SELECT * FROM ligne WHERE id_note = :id_note";
                $result = $laDATA->prepare($sql1);
                $result->execute(array(":id_note" => $idNote));
                $row = $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $ex) {
                die("Erreur lors de la requête SQL : " . $ex->getMessage());
            }
            ?>
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
                echo "<td>" . $rows['id_ligne'] . "</td>";
                echo "<td>" . $rows['date_ligne'] . "</td>";
                echo "<td>" . $rows['lib_trajet'] . "</td>";
                echo "<td>" . $rows['nb_km'] . "</td>";
                echo "<td>" . $rows['mt_peage'] . "</td>";
                echo "<td>" . $rows['mt_repas'] . "</td>";
                echo "<td>" . $rows['mt_hebergement'] . "</td>";
                echo "<td>" . $rows['mt_total'] . "</td>";
                echo "<td>" . $rows['id_motif'] . "</td>";
                echo "</tr>";
            }
        echo "</table>";
        echo "<form method='POST' action='valider.php'>";
        echo "<input type='submit' value='Valider' name='submitValider'>";
        echo "<input type='hidden' value='".$_POST['idnote']."' name='idnote'>";
        echo "</form>";
    }

    if(isset($_POST['submitValider']))
    {
        $sql="UPDATE note SET est_valide = '1' WHERE note.id_note = ".$_POST['idnote'];
        $bdd = $laDATA->prepare($sql);
        $bdd->execute();
        header('Location: controleur.php');
    }
    

   ?>

</body>
</html>